<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Invitation;
use App\Models\User;

class InvitationRepository
{
    public function createInvitation(array $data): Invitation
    {
        return Invitation::create($data);
    }

    public function getInvitations(int $siteSettingId, array $filters = [])
    {
        $query = Invitation::with(['inviter', 'gym', 'membership', 'usedBy']);

        if (isset($filters['branch_id'])) {
            $query->whereHas('gym.branches', function($q) use ($filters) {
                $q->where('id', $filters['branch_id']);
            });
        }

        if (isset($filters['status'])) {
            switch ($filters['status']) {
                case 'active':
                    $query->active();
                    break;
                case 'used':
                    $query->used();
                    break;
                case 'expired':
                    $query->expired();
                    break;
            }
        }

        if (isset($filters['membership_id'])) {
            $query->where('membership_id', $filters['membership_id']);
        }

        // Search functionality
        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('invitee_email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('invitee_phone', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('invitee_name', 'like', "%{$filters['search']}%")
                  ->orWhereHas('inviter', function($inviterQuery) use ($filters) {
                      $inviterQuery->where('name', 'like', "%{$filters['search']}%");
                  })
                  ->orWhereHas('gym', function($gymQuery) use ($filters) {
                      $gymQuery->where('gym_name', 'like', "%{$filters['search']}%");
                  });
            });
        }

        return $query->where('site_setting_id', $siteSettingId)
                     ->orderBy('created_at', 'desc')
                     ->paginate(15);
    }

    public function findByQrCode(string $qrCode): ?Invitation
    {
        return Invitation::where('qr_code', $qrCode)->first();
    }

    public function getUserInvitations(int $userId, int $siteSettingId, array $with = []): Collection
    {
        return Invitation::with($with)
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserActiveInvitations(int $userId, int $siteSettingId): Collection
    {
        return Invitation::active()
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserUsedInvitations(int $userId, int $siteSettingId): Collection
    {
        return Invitation::used()
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->orderBy('used_at', 'desc')
            ->get();
    }

    public function getUserExpiredInvitations(int $userId, int $siteSettingId): Collection
    {
        return Invitation::expired()
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->orderBy('expires_at', 'desc')
            ->get();
    }

    public function getInvitationsByEmail(string $email, int $siteSettingId): Collection
    {
        return Invitation::where('invitee_email', $email)
            ->where('site_setting_id', $siteSettingId)
            ->get();
    }

    public function getInvitationsByPhone(string $phone, int $siteSettingId): Collection
    {
        return Invitation::where('invitee_phone', $phone)
            ->where('site_setting_id', $siteSettingId)
            ->get();
    }

    public function markInvitationAsUsed(Invitation $invitation, User $user): void
    {
        $invitation->markAsUsed($user);
    }

    public function deleteInvitation(Invitation $invitation): void
    {
        $invitation->delete();
    }
}
