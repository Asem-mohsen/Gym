<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\GymCheckinSetting;
use App\Models\Gallery;
use App\Models\BlogPost;
use App\Models\ClassModel;
use App\Models\Service;
use App\Models\User;

class GymFeatureService
{
    /**
     * Check if checkin functionality is enabled for the gym
     */
    public function hasCheckinEnabled(int $siteSettingId): bool
    {
        $checkinSetting = GymCheckinSetting::where('site_setting_id', $siteSettingId)->first();
        
        if (!$checkinSetting) {
            return false;
        }
        
        return $checkinSetting->enable_self_scan || $checkinSetting->enable_gate_scan;
    }

    /**
     * Check if gym has any classes
     */
    public function hasClasses(int $siteSettingId): bool
    {
        return ClassModel::where('site_setting_id', $siteSettingId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if gym has any services
     */
    public function hasServices(int $siteSettingId): bool
    {
        return Service::where('site_setting_id', $siteSettingId)
            ->where('is_available', true)
            ->exists();
    }

    /**
     * Check if gym has any gallery items
     */
    public function hasGallery(int $siteSettingId): bool
    {
        return Gallery::where('site_setting_id', $siteSettingId)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if gym has any blog posts
     */
    public function hasBlog(int $siteSettingId): bool
    {
        return BlogPost::whereHas('user.gyms', function ($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })
        ->where('status', 'published')
        ->exists();
    }

    /**
     * Check if gym has any team members (trainers)
     */
    public function hasTeam(int $siteSettingId): bool
    {
        return User::whereHas('gyms', function ($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })
        ->whereHas('roles', function ($query) {
            $query->where('name', 'trainer');
        })
        ->exists();
    }

    /**
     * Get all feature availability for a gym
     */
    public function getFeatureAvailability(int $siteSettingId): array
    {
        return [
            'checkin' => $this->hasCheckinEnabled($siteSettingId),
            'classes' => $this->hasClasses($siteSettingId),
            'services' => $this->hasServices($siteSettingId),
            'gallery' => $this->hasGallery($siteSettingId),
            'blog' => $this->hasBlog($siteSettingId),
            'team' => $this->hasTeam($siteSettingId),
        ];
    }

    /**
     * Check if gym has any features that would require navigation items
     */
    public function hasAnyFeatures(int $siteSettingId): bool
    {
        $features = $this->getFeatureAvailability($siteSettingId);
        return in_array(true, $features);
    }
}
