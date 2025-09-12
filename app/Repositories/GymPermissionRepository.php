<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\{Permission, Role};
use App\Services\PermissionAssignmentService;

class GymPermissionRepository
{
    /**
     * Assign permissions to a role for a specific gym
     */
    public function assignPermissionsToRole(Role $role, array $permissionNames, int $siteSettingId): void
    {
        $permissionService = app(PermissionAssignmentService::class);
        $permissionService->assignPermissionsToRoleForSite($role, $permissionNames, $siteSettingId);
    }

    /**
     * Assign permissions to a user for a specific gym
     */
    public function assignPermissionsToUser(User $user, array $permissionNames, int $siteSettingId): void
    {
        DB::table('model_has_permissions')
            ->where('model_type', User::class)
            ->where('model_id', $user->id)
            ->where('site_setting_id', $siteSettingId)
            ->delete();

        if (empty($permissionNames)) {
            return;
        }

        $permissions = Permission::whereIn('name', $permissionNames)->get()->keyBy('name');
        
        $insertData = [];
        foreach ($permissionNames as $permissionName) {
            if (isset($permissions[$permissionName])) {
                $permission = $permissions[$permissionName];
                $insertData[] = [
                    'permission_id' => $permission->id,
                    'model_type' => User::class,
                    'model_id' => $user->id,
                    'site_setting_id' => $siteSettingId,
                ];
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
