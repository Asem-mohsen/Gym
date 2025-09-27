<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Observers\{SiteSettingObserver, PermissionTableObserver};
use App\Http\View\Composers\GymFeatureComposer;
use Illuminate\Support\ServiceProvider;
use App\Services\GymBrandingService;
use Illuminate\Support\Facades\View;

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

        View::composer('*', GymFeatureComposer::class);
    }
}
