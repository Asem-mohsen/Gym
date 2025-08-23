<?php

namespace App\Repositories;

use App\Models\ClassModel;

class ClassRepository
{
    public function getClasses(int $siteSettingId)
    {
        return ClassModel::where('site_setting_id', $siteSettingId)->where('status', 'active')->with('schedules', 'pricings','trainers')->get();
    }

    public function getClassesWithSchedules(int $siteSettingId)
    {
        return ClassModel::where('site_setting_id', $siteSettingId)
            ->where('status', 'active')
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

    public function getAll($with = [], $perPage = 15, $search = null, $type = null)
    {
        $query = ClassModel::with($with);
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($type) {
            $query->where('type', $type);
        }
        
        return $query->paginate($perPage);
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