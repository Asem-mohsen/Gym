<?php 
namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function getAllRoles(array $select = ['*'], array $with = [], array $where = [], array $except = [], array $orderBy = [], array $withCount = [])
    {
        return Role::select($select)
            ->when(!empty($with), fn($query) => $query->with($with))
            ->when(!empty($where), fn($query) => $query->where($where))
            ->when(!empty($except), fn($query) => $query->whereNotIn('name', $except))
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }
            })
            ->when(!empty($withCount), fn($query) => $query->withCount($withCount))
            ->get();
    }

    public function getRoleByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }
}