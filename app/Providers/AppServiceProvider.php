<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Observers\{SiteSettingObserver, PermissionTableObserver};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        SiteSetting::observe(SiteSettingObserver::class);

        PermissionTableObserver::watchRolePermissions();
        PermissionTableObserver::watchModelPermissions();
    }
}
