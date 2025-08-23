<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DocumentRepository;

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
            
            if ($site) {
                $documentRepository = app(DocumentRepository::class);
                $documentCount = $documentRepository->getTotalDocumentsForGym($site->id);
                $view->with('documentCount', $documentCount);
            } else {
                $view->with('documentCount', 0);
            }
        });
        
        View::composer('layout.admin.footer.footer', function ($view) {
            $site = Auth::user()?->site;
            $view->with('site', $site);
        });
    }
}
