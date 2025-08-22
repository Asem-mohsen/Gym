<?php

namespace App\Repositories;

use App\Models\Invitation;
use App\Models\User;
use App\Models\SiteSetting;

class InvitationRepository
{
    public function createInvitation(array $data): Invitation
    {
        return Invitation::create($data);
    }

    public function findById(int $id): ?Invitation
    {
        return Invitation::find($id);
    }

    public function findByQrCode(string $qrCode): ?Invitation
    {
        return Invitation::where('qr_code', $qrCode)->first();
    }

    public function getUserInvitations(int $userId, int $siteSettingId, array $with = []): \Illuminate\Database\Eloquent\Collection
    {
        return Invitation::with($with)
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserActiveInvitations(int $userId, int $siteSettingId): \Illuminate\Database\Eloquent\Collection
    {
        return Invitation::active()
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserUsedInvitations(int $userId, int $siteSettingId): \Illuminate\Database\Eloquent\Collection
    {
        return Invitation::used()
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->orderBy('used_at', 'desc')
            ->get();
    }

    public function getUserExpiredInvitations(int $userId, int $siteSettingId): \Illuminate\Database\Eloquent\Collection
    {
        return Invitation::expired()
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->orderBy('expires_at', 'desc')
            ->get();
    }

    public function getInvitationsByEmail(string $email, int $siteSettingId): \Illuminate\Database\Eloquent\Collection
    {
        return Invitation::where('invitee_email', $email)
            ->where('site_setting_id', $siteSettingId)
            ->get();
    }

    public function getInvitationsByPhone(string $phone, int $siteSettingId): \Illuminate\Database\Eloquent\Collection
    {
        return Invitation::where('invitee_phone', $phone)
            ->where('site_setting_id', $siteSettingId)
            ->get();
    }

    public function countUserInvitations(int $userId, int $siteSettingId): int
    {
        return Invitation::where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->count();
    }

    public function countUserActiveInvitations(int $userId, int $siteSettingId): int
    {
        return Invitation::active()
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->count();
    }

    public function countUserUsedInvitations(int $userId, int $siteSettingId): int
    {
        return Invitation::used()
            ->where('inviter_id', $userId)
            ->where('site_setting_id', $siteSettingId)
            ->count();
    }

    public function deleteInvitation(Invitation $invitation): bool
    {
        return $invitation->delete();
    }

    public function markInvitationAsUsed(Invitation $invitation, User $user): void
    {
        $invitation->markAsUsed($user);
    }
}
