<?php

namespace App\Repositories;

use App\Models\Feature;

class FeatureRepository
{
    public function getAllFeatures(int $siteSettingId, array $select = ['*'], array $with = [], array $where = [], array $orderBy = [], array $withCount = [])
    {
        return Feature::select($select)
            ->where('site_setting_id', $siteSettingId)
            ->when(!empty($with), fn ($query) => $query->with($with))
            ->when(!empty($where), fn ($query) => $query->where($where))
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }
            })
            ->when(!empty($withCount), fn ($query) => $query->withCount($withCount))
            ->get();
    }

    public function createFeature(array $data)
    {
        return Feature::create($data);
    }

    public function updateFeature(Feature $feature, array $data)
    {
        $feature->update($data);
        return $feature;
    }

    public function deleteFeature(Feature $feature)
    {
        $feature->delete();
    }

    public function findById(int $id): ?Feature
    {
        return Feature::find($id);
    }

    public function selectFeatures(int $siteSettingId)
    {
        return Feature::where('status', 1)
            ->where('site_setting_id', $siteSettingId)
            ->select('id', 'name')
            ->orderBy('order')
            ->get()
            ->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->getTranslation('name', app()->getLocale()),
                ];
            });
    }
} 