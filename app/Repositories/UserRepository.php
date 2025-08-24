<?php 
namespace App\Repositories;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRepository
{
    public function getAllUsers(int $siteSettingId, $perPage = 15, $branchId = null, $search = null)
    {
        $query = User::where('is_admin', '0')
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            });

        if ($branchId) {
            $query->whereHas('subscriptions', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->with('roles')->paginate($perPage);
    }

    public function getAllTrainers(int $siteSettingId, $perPage = 15, $branchId = null, $search = null)
    {
        $trainerRole = Role::where('name', 'trainer')->first();
        
        $query = User::where('is_admin', '0')
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            });

        if ($trainerRole) {
            $query->whereHas('roles', function ($query) use ($trainerRole) {
                $query->where('roles.id', $trainerRole->id);
            });
        }

        if ($branchId) {
            $query->whereHas('subscriptions', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->with('roles')->paginate($perPage);
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function updateUser(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user)
    {
        $user->bookings()->delete();
        $user->coachingSessions()->delete();
        $user->gyms()->detach();
        $user->tokens()->delete();
        $user->delete();
    }

    public function findById(int $id, array $with = []): ?User
    {
        return User::with($with)->find($id);
    }

    /**
     * Get users by role name
     */
    public function getUsersByRole(string $roleName, int $siteSettingId, $perPage = 15, $search = null)
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return collect()->paginate($perPage);
        }

        $query = User::where('is_admin', '0')
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('roles.id', $role->id);
            });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->with('roles')->paginate($perPage);
    }
}