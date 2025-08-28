<?php

namespace App\Observers;

use App\Traits\ClearsPermissionCache;
use Illuminate\Support\Facades\{DB, Artisan, Log};
use Spatie\Permission\PermissionRegistrar;

class PermissionTableObserver
{
    use ClearsPermissionCache;

    /**
     * Watch for changes in role_has_permissions table
     */
    public static function watchRolePermissions(): void
    {
        // Listen for database events on role_has_permissions table
        DB::listen(function ($query) {
            if (str_contains($query->sql, 'role_has_permissions')) {
                self::clearPermissionCache();
            }
        });
    }

    /**
     * Watch for changes in model_has_permissions table
     */
    public static function watchModelPermissions(): void
    {
        // Listen for database events on model_has_permissions table
        DB::listen(function ($query) {
            if (str_contains($query->sql, 'model_has_permissions')) {
                self::clearPermissionCache();
            }
        });
    }

    /**
     * Clear permission cache (static method for use in DB listeners)
     */
    private static function clearPermissionCache(): void
    {
        try {
            // Clear Spatie permission cache
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            
            // Clear Laravel cache
            Artisan::call('cache:clear');
            
            // Clear config cache
            Artisan::call('config:clear');
            
            // Clear route cache
            Artisan::call('route:clear');
            
            // Clear view cache
            Artisan::call('view:clear');

            Artisan::call('optimize:clear');
        } catch (\Exception $e) {
            // Log error but don't fail the operation
            Log::warning('Failed to clear permission cache in PermissionTableObserver: ' . $e->getMessage());
        }
    }
}
