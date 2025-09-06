<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\{Permission, Role};

class GymPermissionRepository
{
    /**
     * Assign permissions to a role for a specific gym
     */
    public function assignPermissionsToRole(Role $role, array $permissionNames, int $siteSettingId): void
    {
        // First, remove existing permissions for this role in this gym
        DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->where('site_setting_id', $siteSettingId)
            ->delete();

        // Prepare batch insert data
        $insertData = [];
        foreach ($permissionNames as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                // Check if this combination already exists to prevent duplicates
                $exists = DB::table('role_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->where('role_id', $role->id)
                    ->where('site_setting_id', $siteSettingId)
                    ->exists();
                
                if (!$exists) {
                    $insertData[] = [
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                        'site_setting_id' => $siteSettingId,
                    ];
                }
            }
        }

        // Batch insert if there's data to insert
        if (!empty($insertData)) {
            DB::table('role_has_permissions')->insert($insertData);
        }
    }

    /**
     * Assign permissions to a user for a specific gym
     */
    public function assignPermissionsToUser(User $user, array $permissionNames, int $siteSettingId): void
    {
        // First, remove existing permissions for this user in this gym
        DB::table('model_has_permissions')
            ->where('model_type', User::class)
            ->where('model_id', $user->id)
            ->where('site_setting_id', $siteSettingId)
            ->delete();

        // Prepare batch insert data
        $insertData = [];
        foreach ($permissionNames as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                // Check if this combination already exists to prevent duplicates
                $exists = DB::table('model_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->where('model_type', User::class)
                    ->where('model_id', $user->id)
                    ->where('site_setting_id', $siteSettingId)
                    ->exists();
                
                if (!$exists) {
                    $insertData[] = [
                        'permission_id' => $permission->id,
                        'model_type' => User::class,
                        'model_id' => $user->id,
                        'site_setting_id' => $siteSettingId,
                    ];
                }
            }
        }

        // Batch insert if there's data to insert
        if (!empty($insertData)) {
            DB::table('model_has_permissions')->insert($insertData);
        }
    }

    /**
     * Get role permissions for a specific gym
     */
    public function getRolePermissions(Role $role, int $siteSettingId): Collection
    {
        return $role->permissions()
            ->wherePivot('site_setting_id', $siteSettingId)
            ->get();
    }

    /**
     * Get user permissions for a specific gym
     */
    public function getUserPermissions(User $user, int $siteSettingId): Collection
    {
        return $user->permissions()
            ->wherePivot('site_setting_id', $siteSettingId)
            ->get();
    }

}
