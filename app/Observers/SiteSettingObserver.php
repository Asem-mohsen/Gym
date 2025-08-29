<?php

namespace App\Observers;

use App\Models\SiteSetting;
use App\Traits\ClearsPermissionCache;
use Illuminate\Support\Facades\DB;

class SiteSettingObserver
{
    use ClearsPermissionCache;
    /**
     * Handle the SiteSetting "created" event.
     */
    public function created(SiteSetting $siteSetting): void
    {
        $this->createDefaultGymPermissions($siteSetting);
    }

    /**
     * Create default gym permissions for a new site setting
     */
    private function createDefaultGymPermissions(SiteSetting $siteSetting): void
    {
        if ($siteSetting->owner_id) {
            $owner = $siteSetting->owner;
            if ($owner && !$owner->hasRole('admin')) {
                $owner->assignRole('admin');
            }
        }
    }

    /**
     * Handle the SiteSetting "updated" event.
     */
    public function updated(SiteSetting $siteSetting): void
    {
        //
    }

    /**
     * Handle the SiteSetting "deleted" event.
     */
    public function deleted(SiteSetting $siteSetting): void
    {
        // Delete all gym role and user permissions for this site setting
        DB::table('role_has_permissions')
            ->where('site_setting_id', $siteSetting->id)
            ->delete();
        DB::table('model_has_permissions')
            ->where('site_setting_id', $siteSetting->id)
            ->delete();

        // Clear cache after gym deletion
        $this->clearPermissionCache();
    }

    /**
     * Handle the SiteSetting "restored" event.
     */
    public function restored(SiteSetting $siteSetting): void
    {
        //
    }

    /**
     * Handle the SiteSetting "force deleted" event.
     */
    public function forceDeleted(SiteSetting $siteSetting): void
    {
        DB::table('role_has_permissions')
            ->where('site_setting_id', $siteSetting->id)
            ->delete();
        DB::table('model_has_permissions')
            ->where('site_setting_id', $siteSetting->id)
            ->delete();

        // Clear cache after gym deletion
        $this->clearPermissionCache();
    }
}
