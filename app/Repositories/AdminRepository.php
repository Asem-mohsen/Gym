<?php 
namespace App\Repositories;

use App\Models\User;

class AdminRepository
{
    public function getAllAdmins(int $siteSettingId, $perPage = 15, $search = null)
    {
        $query = User::where('is_admin', '1')->with('role')
                ->whereHas('gyms', function ($query)use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                });
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        return $query->paginate($perPage);
    }

    public function createAdmin(array $data)
    {
        return User::create($data);
    }

    public function updateAdmin(User $user, array $data)
    {
        $user->update($data);

        return $user;
    }

    public function deleteAdmin(User $user)
    {
        return $user->delete();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }
}
