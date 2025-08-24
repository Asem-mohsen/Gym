<?php 
namespace App\Services;

use App\Repositories\RoleRepository;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRoles(array $where = [] ,array $withCount = [])
    {
        return $this->roleRepository->getAllRoles(where: $where , withCount: $withCount);
    }


    /**
     * Get roles for regular user creation (only regular_user role)
     */
    public function getRolesForUserCreation(): array
    {
        $regularUserRole = SpatieRole::whereIn('name', ['regular_user', 'trainer'])->get();
        $formattedRoles = [];
        
        foreach ($regularUserRole as $role) {
            $formattedRoles[$role->id] = $role;
        }

        return $formattedRoles;
    }

    /**
     * Get roles for admin creation (all roles except regular_user)
     */
    public function getRolesForAdminCreation(): array
    {
        $roles = SpatieRole::where('name', '!=', 'regular_user')->get();
        $formattedRoles = [];
        
        foreach ($roles as $role) {
            $formattedRoles[$role->id] = $role;
        }
        
        return $formattedRoles;
    }

    /**
     * Get all roles for admin management
     */
    public function getAllRolesForAdmin(): array
    {
        $roles = SpatieRole::all();
        $formattedRoles = [];
        
        foreach ($roles as $role) {
            $formattedRoles[$role->id] = $role;
        }
        
        return $formattedRoles;
    }

}
