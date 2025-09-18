<?php

namespace App\Repositories;

use App\Models\ClassModel;

class ClassRepository
{
    public function getClasses(int $siteSettingId)
    {
        return ClassModel::where('site_setting_id', $siteSettingId)
            ->where('status', 'active')
            ->whereHas('branches')
            ->with('schedules', 'pricings','trainers')
            ->get();
    }

    public function getClassesWithSchedules(int $siteSettingId)
    {
        return ClassModel::where('site_setting_id', $siteSettingId)
            ->where('status', 'active')
            ->whereHas('branches')
            ->with(['schedules', 'trainers'])
            ->get();
    }

    public function getClassTypes(int $siteSettingId)
    {
        return ClassModel::where('site_setting_id', $siteSettingId)
            ->where('status', 'active')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->values();
    }

    public function getAll(array $where = [], array $with = [], ?string $type = null, ?int $branchId = null)
    {
        $query = ClassModel::with($with)
            ->when($branchId, function($query) use ($branchId) {
                $query->whereHas('branches', function($query) use ($branchId) {
                    $query->where('branch_id', $branchId)->where('is_visible', true);
                });
            });
        
        if ($where) {
            $query->where($where);
        }
        
        if ($type) {
            $query->where('type', $type);
        }
        
        return $query->get();
    }

    public function findById($id, $with = [])
    {
        return ClassModel::with($with)->findOrFail($id);
    }

    public function create(array $data)
    {
        return ClassModel::create($data);
    }

    public function update(ClassModel $class, array $data)
    {
        $class->update($data);
        return $class;
    }

    public function delete(ClassModel $class)
    {
        return $class->delete();
    }
} 