<?php

namespace App\Traits;

use Illuminate\Support\Facades\{Artisan, Log};
use Spatie\Permission\PermissionRegistrar;

trait ClearsPermissionCache
{
    /**
     * Clear permission cache to ensure changes take effect immediately
     */
    private function clearPermissionCache(): void
    {
        try {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            
            Artisan::call('cache:clear');
            
            Artisan::call('config:clear');
            
            Artisan::call('route:clear');
            
            Artisan::call('view:clear');

            Artisan::call('optimize:clear');
        } catch (\Exception $e) {
            Log::warning('Failed to clear permission cache in ' . static::class . ': ' . $e->getMessage());
        }
    }
}
