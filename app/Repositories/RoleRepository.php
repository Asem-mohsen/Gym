<?php 
namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function getAllRoles(int $siteSettingId , array $select = ['*'], array $with = [], array $where = [], array $orderBy = [], array $withCount = [])
    {
        return Role::select($select)
            ->when(!empty($with), fn($query) => $query->with($with))
            ->when(!empty($where), fn($query) => $query->where($where))
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }
            })
            ->when(!empty($withCount), fn($query) => $query->withCount($withCount))
            ->where('site_setting_id', $siteSettingId)
            ->get();
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

    public function findWith(array $select = ['*'], array $with = [], array $where = [], array $orderBy = [] , array $withCount = []): ?Role
    {
        return Role::select($select)
            ->when(! empty($withCount), fn ($query) => $query->withCount($withCount))
            ->when(! empty($with), fn ($query) => $query->with($with))
            ->when(! empty($where), fn ($query) => $query->where($where))
            ->when(! empty($orderBy), function ($query) use ($orderBy) {
                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }
            })
            ->when(! empty($withCount), fn ($query) => $query->withCount($withCount))
            ->first();
        
    }
}