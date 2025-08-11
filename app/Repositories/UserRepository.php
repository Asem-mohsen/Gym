<?php 
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAllUsers(int $siteSettingId)
    {
        return User::where('is_admin' , '0')->where('role_id', '2')
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->get();
    }

    public function getAllTrainers(int $siteSettingId)
    {
        return User::where('is_admin' , '0')->where('role_id', '3')
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->get();
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