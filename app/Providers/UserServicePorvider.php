<?php

namespace App\Providers;

use App\Repositories\BlogRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Services\BlogService;
use App\Services\GymContextService;

class UserServicePorvider extends ServiceProvider
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
        View::composer('layout.user.footer.footer', function ($view) {
            $gymContextService = app(GymContextService::class);
            $currentGymContext = $gymContextService->getCurrentGymContext();
            
            if ($currentGymContext && isset($currentGymContext['id'])) {
                $blogService = new BlogService(new BlogRepository());
                
                try {
                    $blogPosts = $blogService->getBlogPosts($currentGymContext['id'], true, 2, 'created_at', 'desc');
                    if ($blogPosts->isNotEmpty()) {
                        $view->with('blogPosts', $blogPosts);
                        return;
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to get blog posts by site: ' . $e->getMessage());
                }
                
                try {
                    $blogPosts = $blogService->getBlogPosts($currentGymContext['id'], true, 2);
                    $view->with('blogPosts', $blogPosts);
                } catch (\Exception $e) {
                    $view->with('blogPosts', collect([]));
                }
            } else {
                $siteSettingService = app(\App\Services\SiteSettingService::class);
                try {
                    $defaultSiteId = $siteSettingService->getCurrentSiteSettingIdOrFallback();
                    $blogService = new BlogService(new BlogRepository());
                    
                    try {
                        $blogPosts = $blogService->getBlogPosts($defaultSiteId, true, 2, 'created_at', 'desc');
                        if ($blogPosts->isNotEmpty()) {
                            $view->with('blogPosts', $blogPosts);
                            return;
                        }
                    } catch (\Exception $e) {
                        Log::warning('Failed to get blog posts by site for default site: ' . $e->getMessage());
                    }
                    
                    $blogPosts = $blogService->getBlogPosts($defaultSiteId, true, 2);
                    $view->with('blogPosts', $blogPosts);
                } catch (\Exception $e) {
                    $view->with('blogPosts', collect([]));
                }
            }
        });
    }
}
