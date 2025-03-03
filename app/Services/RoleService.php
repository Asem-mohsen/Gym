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

    public function getRoles(int $siteSettingId ,array $where = [] ,array $withCount = [])
    {
        return $this->roleRepository->getAllRoles(siteSettingId: $siteSettingId , where: $where , withCount: $withCount);
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
        return $this->roleRepository->findWith(where:['id' => $role->id] ,with:['users'] , withCount:['users']);
    }

    public function deleteRole($role)
    {
        return $this->roleRepository->deleteRole($role);
    }
}
