<?php 
namespace App\Repositories;

use App\Models\User;

class AdminRepository
{
    public function getAllAdmins()
    {
        return User::where('is_admin', '1')->with('roles')->get();
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
