<?php

namespace App\Repositories;

use App\Models\ClassModel;

class ClassRepository
{
    public function getClasses(int $siteSettingId)
    {
        return ClassModel::where('site_setting_id', $siteSettingId)->where('status', 'active')->with('schedules', 'pricings','trainers')->get();
    }

    public function getAll($with = [])
    {
        return ClassModel::with($with)->get();
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