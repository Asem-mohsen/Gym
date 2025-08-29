<?php

namespace App\Repositories;

use App\Models\GymCheckinSetting;

class CheckinSettingRepository
{
    /**
     * Get check-in settings by gym ID
     */
    public function getByGymId(int $gymId): ?GymCheckinSetting
    {
        return GymCheckinSetting::where('site_setting_id', $gymId)->first();
    }

    /**
     * Create check-in settings
     */
    public function create(array $data): GymCheckinSetting
    {
        return GymCheckinSetting::create($data);
    }

    /**
     * Update check-in settings
     */
    public function update(GymCheckinSetting $checkinSetting, array $data): GymCheckinSetting
    {
        $checkinSetting->update($data);
        return $checkinSetting->fresh();
    }

    /**
     * Delete check-in settings
     */
    public function delete(GymCheckinSetting $checkinSetting): bool
    {
        return $checkinSetting->delete();
    }

    /**
     * Find check-in settings by ID
     */
    public function findById(int $id): ?GymCheckinSetting
    {
        return GymCheckinSetting::find($id);
    }

    /**
     * Get all check-in settings
     */
    public function getAll()
    {
        return GymCheckinSetting::with('gym')->get();
    }

    /**
     * Check if gym has check-in settings
     */
    public function existsByGymId(int $gymId): bool
    {
        return GymCheckinSetting::where('site_setting_id', $gymId)->exists();
    }
}
