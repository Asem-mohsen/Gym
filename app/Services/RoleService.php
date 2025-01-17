<?php 
namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRoles()
    {
        return $this->roleRepository->getAllRoles();
    }

    public function createRole(array $data)
    {
        return $this->roleRepository->createRole($data);
    }

    public function updateRole($role, array $data)
    {
        return $this->roleRepository->updateRole($role, $data);
    }

    public function showRole($role)
    {
        return $this->roleRepository->findById($role->id);
    }

    public function deleteRole($role)
    {
        return $this->roleRepository->deleteRole($role);
    }
}
