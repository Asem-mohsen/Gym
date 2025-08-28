<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleAssignmentService
{
    /**
     * Assign roles to a user
     */
    public function assignRolesToUser(User $user, array $roleIds): void
    {
        try {
            if (!empty($roleIds)) {
                $roles = Role::whereIn('id', $roleIds)->get();
                $user->syncRoles($roles);
                
                Log::info("Roles assigned to user {$user->id}: " . $roles->pluck('name')->implode(', '));
            } else {
                // Assign default role based on user type
                $this->assignDefaultRole($user);
            }
        } catch (\Exception $e) {
            Log::error("Failed to assign roles to user {$user->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Assign default role based on user type
     */
    public function assignDefaultRole(User $user): void
    {
        if ($user->is_admin) {
            $this->assignAdminRole($user);
        } else {
            $this->assignRegularUserRole($user);
        }
    }

    /**
     * Assign admin role to user
     */
    public function assignAdminRole(User $user): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        
        if ($adminRole) {
            $user->assignRole($adminRole);
            Log::info("Admin role assigned to user {$user->id}");
        } else {
            Log::warning("Admin role not found for user {$user->id}");
        }
    }

    /**
     * Assign regular user role
     */
    public function assignRegularUserRole(User $user): void
    {
        $regularUserRole = Role::where('name', 'regular_user')->first();
        
        if ($regularUserRole) {
            $user->assignRole($regularUserRole);
            Log::info("Regular user role assigned to user {$user->id}");
        } else {
            Log::warning("Regular user role not found for user {$user->id}");
        }
    }

    /**
     * Get roles suitable for admin assignment
     */
    public function getAdminRoles(): array
    {
        return Role::whereIn('name', ['admin', 'management', 'sales'])->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description ?? '',
                'permissions' => $role->permissions->pluck('name')->toArray()
            ];
        })->toArray();
    }

    /**
     * Get roles suitable for regular user assignment
     */
    public function getUserRoles(): array
    {
        return Role::whereIn('name', ['trainer', 'sales', 'management', 'regular_user'])->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description ?? '',
                'permissions' => $role->permissions->pluck('name')->toArray()
            ];
        })->toArray();
    }
}
