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

    public function getRoles(array $where = [] ,array $withCount = [], array $except = [])
    {
        return $this->roleRepository->getAllRoles(where: $where , withCount: $withCount, except: $except);
    }
}
