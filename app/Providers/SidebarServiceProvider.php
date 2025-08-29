<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DocumentRepository;
use App\Models\User;

class SidebarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        View::composer('layout.admin.sidebar.sidebar', function ($view) {
            $user = Auth::user();
            $site = null;
            
            if ($user) {
                // Try to get site from user's relationships first
                $site = $user->getCurrentSite();
                
                // If still no site, try to get from gym context
                if (!$site) {
                    $gymContextService = app(\App\Services\GymContextService::class);
                    $gymContext = $gymContextService->getCurrentGymContext();
                    
                    if ($gymContext && isset($gymContext['id'])) {
                        $site = SiteSetting::find($gymContext['id']);
                    }
                }
            }
            
            $view->with('site', $site);
            
            if ($site) {
                $documentRepository = app(DocumentRepository::class);
                $documentCount = $documentRepository->getTotalDocumentsForGym($site->id);
                $view->with('documentCount', $documentCount);
            } else {
                $view->with('documentCount', 0);
            }
        });
        
        View::composer('layout.admin.footer.footer', function ($view) {
            /**
             * @var User|null $user
             */
            $user = Auth::user();
            $site = null;
            
            if ($user) {
                $site = $user->getCurrentSite();
                
                if (!$site) {
                    $gymContextService = app(\App\Services\GymContextService::class);
                    $gymContext = $gymContextService->getCurrentGymContext();
                    
                    if ($gymContext && isset($gymContext['id'])) {
                        $site = SiteSetting::find($gymContext['id']);
                    }
                }
            }
            
            $view->with('site', $site);
        });
    }
}
