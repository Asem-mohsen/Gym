<?php 
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAllUsers(int $siteSettingId, $perPage = 15, $branchId = null, $search = null)
    {
        $query = User::where('is_admin' , '0')->where('role_id', '2')
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

        return $query->paginate($perPage);
    }

    public function getAllTrainers(int $siteSettingId, $perPage = 15, $branchId = null, $search = null)
    {
        $query = User::where('is_admin' , '0')->where('role_id', '3')
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

        return $query->paginate($perPage);
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function updateUser(User $user , array $data)
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
}