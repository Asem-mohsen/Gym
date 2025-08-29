<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'roles';
    protected $guarded = ['id'];

    /**
     * Get gym-specific permissions for this role
     */
    public function getGymPermissions(int $siteSettingId): Collection
    {
        return $this->permissions()
            ->wherePivot('site_setting_id', $siteSettingId)
            ->get();
    }

    /**
     * Check if role has a specific gym permission
     */
    public function hasGymPermission(string $permissionName, int $siteSettingId): bool
    {
        return $this->permissions()
            ->where('name', $permissionName)
            ->wherePivot('site_setting_id', $siteSettingId)
            ->exists();
    }

    /**
     * Assign a permission to this role for a specific gym
     */
    public function assignGymPermission(string $permissionName, int $siteSettingId): void
    {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            $this->permissions()->attach($permission->id, ['site_setting_id' => $siteSettingId]);
        }
    }

    /**
     * Remove a permission from this role for a specific gym
     */
    public function removeGymPermission(string $permissionName, int $siteSettingId): void
    {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            $this->permissions()->wherePivot('site_setting_id', $siteSettingId)->detach($permission->id);
        }
    }
}
