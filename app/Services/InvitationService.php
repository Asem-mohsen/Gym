<?php

namespace App\Services;

use App\Models\{Invitation, User, SiteSetting, Subscription};
use App\Repositories\InvitationRepository;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\{DB, Mail};
use Exception;
use Illuminate\Support\Str;

class InvitationService
{
    public function __construct(
        protected InvitationRepository $invitationRepository
    ) {}

    public function getInvitations(int $siteSettingId, array $filters = [])
    {
        return $this->invitationRepository->getInvitations($siteSettingId, $filters);
    }

    /**
     * Send an invitation to a new user
     */
    public function sendInvitation(array $data, User $inviter, SiteSetting $gym): Invitation
    {
        // Check if user has active subscription with invitation feature
        $subscription = $this->getUserActiveSubscription($inviter, $gym);
        
        if (!$subscription) {
            throw new Exception('You need an active subscription to send invitations.');
        }

        if (!$subscription->canSendInvitation()) {
            throw new Exception('You have reached your invitation limit for this membership.');
        }

        // Check if invitation already exists for this email/phone
        $existingInvitation = $this->checkExistingInvitation($data['invitee_email'], $data['invitee_phone'], $gym->id);
        
        if ($existingInvitation) {
            throw new Exception('An invitation has already been sent to this email or phone number.');
        }

        DB::beginTransaction();
        
        try {
            $invitationData = [
                'inviter_id' => $inviter->id,
                'site_setting_id' => $subscription->branch->site_setting_id,
                'membership_id' => $subscription->membership_id,
                'invitee_email' => $data['invitee_email'],
                'invitee_phone' => $data['invitee_phone'],
                'invitee_name' => $data['invitee_name'] ?? null,
            ];

            $invitation = $this->invitationRepository->createInvitation($invitationData);

            // Increment invitations used count
            $subscription->increment('invitations_used');

            // Send invitation email
            $this->sendInvitationEmail($invitation);

            DB::commit();
            
            return $invitation;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Verify and use an invitation via QR code
     */
    public function verifyAndUseInvitation(string $qrCode, User $user): Invitation
    {
        $invitation = $this->invitationRepository->findByQrCode($qrCode);
        
        if (!$invitation) {
            throw new Exception('Invalid invitation QR code.');
        }

        if (!$invitation->canBeUsed()) {
            throw new Exception('This invitation has already been used or has expired.');
        }

        DB::beginTransaction();
        
        try {
            // Mark invitation as used
            $this->invitationRepository->markInvitationAsUsed($invitation, $user);

            DB::commit();
            
            return $invitation;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get user's invitations with statistics
     */
    public function getUserInvitations(User $user, SiteSetting $gym): array
    {
        $invitations = $this->invitationRepository->getUserInvitations($user->id, $gym->id, ['membership']);
        $activeInvitations = $this->invitationRepository->getUserActiveInvitations($user->id, $gym->id);
        $usedInvitations = $this->invitationRepository->getUserUsedInvitations($user->id, $gym->id);
        $expiredInvitations = $this->invitationRepository->getUserExpiredInvitations($user->id, $gym->id);

        $subscription = $this->getUserActiveSubscription($user, $gym);
        $remainingInvitations = $subscription ? $subscription->remaining_invitations : 0;

        return [
            'invitations' => $invitations,
            'active_invitations' => $activeInvitations,
            'used_invitations' => $usedInvitations,
            'expired_invitations' => $expiredInvitations,
            'remaining_invitations' => $remainingInvitations,
            'subscription' => $subscription,
        ];
    }

    /**
     * Get user's active subscription for the gym
     */
    private function getUserActiveSubscription(User $user, SiteSetting $gym): ?Subscription
    {
        return $user->subscriptions()
            ->whereHas('branch', function($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })
            ->where('end_date', '>', now())
            ->where('status', 'active')
            ->with(['membership', 'branch'])
            ->first();
    }

    /**
     * Check if invitation already exists for email/phone
     */
    private function checkExistingInvitation(string $email, string $phone, int $siteSettingId): ?Invitation
    {
        $emailInvitation = $this->invitationRepository->getInvitationsByEmail($email, $siteSettingId)->first();
        $phoneInvitation = $this->invitationRepository->getInvitationsByPhone($phone, $siteSettingId)->first();

        return $emailInvitation ?? $phoneInvitation;
    }

    /**
     * Send invitation email
     */
    private function sendInvitationEmail(Invitation $invitation): void
    {
        Mail::to($invitation->invitee_email)->send(new InvitationMail($invitation));
    }

    /**
     * Resend an expired invitation
     */
    public function resendInvitation(Invitation $invitation, User $user): Invitation
    {
        // Check if user owns this invitation
        if ($invitation->inviter_id !== $user->id) {
            throw new Exception('You can only resend your own invitations.');
        }

        // Check if invitation is expired
        if (!$invitation->isExpired()) {
            throw new Exception('Only expired invitations can be resent.');
        }

        // Check if user has active subscription
        $subscription = $this->getUserActiveSubscription($user, $invitation->gym);
        
        if (!$subscription) {
            throw new Exception('You need an active subscription to resend invitations.');
        }

        DB::beginTransaction();
        
        try {
            $invitation->update([
                'qr_code' => Str::random(32),
                'expires_at' => now()->addDays(30),
                'is_used' => false,
                'used_at' => null,
                'used_by_id' => null,
            ]);

            $this->sendInvitationEmail($invitation);

            DB::commit();
            
            return $invitation;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
