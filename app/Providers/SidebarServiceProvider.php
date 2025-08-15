<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Repositories\BlogRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\BlogService;
use Illuminate\Support\Facades\Auth;

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
            $site = Auth::user()?->site;
            $view->with('site', $site);
        });
        View::composer('layout.admin.footer.footer', function ($view) {
            $site = Auth::user()?->site;
            $view->with('site', $site);
        });

        View::composer('layout.user.footer.footer', function ($view) {
            $site = Auth::user()?->site;
            $blogService = new BlogService(new BlogRepository());
            $blogPosts = $blogService->getBlogPosts($site->id, true, 2);
            $view->with('blogPosts', $blogPosts);
        });
    }
}
