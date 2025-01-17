<?php 
namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function getAllRoles()
    {
        return Role::all();
    }

    public function createRole(array $data)
    {
        return Role::create($data);
    }

    public function updateRole(Role $role , array $data)
    {
        $role->update($data);
        return $role;
    }

    public function deleteRole(Role $role)
    {
        $role->delete();
    }

    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }
}