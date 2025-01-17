<?php 
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAllUsers()
    {
        return User::where('is_admin' , '0')->where('role_id', '2')->get();
    }

    public function getAllTrainers()
    {
        return User::where('is_admin' , '0')->where('role_id', '3')->get();
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
        $user->delete();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }
}