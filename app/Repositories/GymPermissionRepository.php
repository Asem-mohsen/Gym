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
        DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->where('site_setting_id', $siteSettingId)
            ->delete();

        foreach ($permissionNames as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $role->id,
                    'site_setting_id' => $siteSettingId,
                ]);
            }
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

        // Then assign new permissions
        foreach ($permissionNames as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                DB::table('model_has_permissions')->insert([
                    'permission_id' => $permission->id,
                    'model_type' => User::class,
                    'model_id' => $user->id,
                    'site_setting_id' => $siteSettingId,
                ]);
            }
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
