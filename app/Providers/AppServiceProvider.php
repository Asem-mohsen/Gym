<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Observers\{SiteSettingObserver, PermissionTableObserver};
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

        // Share gym branding data with all views
        // View::composer('*', function ($view) {
        //     try {
        //         $gymBrandingService = app(GymBrandingService::class);
        //         $branding = $gymBrandingService->getCurrentGymBranding(auth()->user()->getCurrentSite()->id);
        //         $cssVariables = $gymBrandingService->generateCssVariables();
                
        //         $view->with([
        //             'gymBranding' => $branding,
        //             'gymCssVariables' => $cssVariables
        //         ]);
        //     } catch (\Exception $e) {
        //         // Fallback to defaults if there's an error
        //         $view->with([
        //             'gymBranding' => [],
        //             'gymCssVariables' => ''
        //         ]);
        //     }
        // });
    }
}
