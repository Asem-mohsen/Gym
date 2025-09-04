<?php

namespace App\Repositories;

use App\Models\GymSetting;

class GymBrandingRepository
{
    /**
     * Get branding settings for a specific gym
     */
    public function getBrandingSettings(int $siteSettingId): ?GymSetting
    {
        return GymSetting::where('site_setting_id', $siteSettingId)->first();
    }

    /**
     * Create or update branding settings for a gym
     */
    public function createOrUpdateBrandingSettings(int $siteSettingId, array $brandingData): GymSetting
    {
        return GymSetting::updateOrCreate(
            ['site_setting_id' => $siteSettingId],
            $brandingData
        );
    }

    /**
     * Delete branding settings for a gym
     */
    public function deleteBrandingSettings(int $siteSettingId): bool
    {
        return GymSetting::where('site_setting_id', $siteSettingId)->delete();
    }


    /**
     * Check if gym has custom branding
     */
    public function hasCustomBranding(GymSetting $gymSetting): bool
    {
        return $gymSetting->getNonNullBrandingValues() !== [];
    }
}
