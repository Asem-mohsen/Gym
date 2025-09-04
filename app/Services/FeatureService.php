<?php

namespace App\Services;

use App\Repositories\FeatureRepository;

class FeatureService
{
    protected $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function getFeatures(int $siteSettingId,array $withCount = [])
    {
        return $this->featureRepository->getAllFeatures(
            siteSettingId: $siteSettingId,
            withCount: $withCount, 
            orderBy: ['order' => 'asc']
        );
    }

    public function createFeature(array $data, int $siteSettingId)
    {
        $data['site_setting_id'] = $siteSettingId;
        return $this->featureRepository->createFeature($data);
    }

    public function updateFeature($feature, array $data)
    {
        return $this->featureRepository->updateFeature($feature, $data);
    }

    public function showFeature($feature)
    {
        return $this->featureRepository->findById($feature->id);
    }

    public function deleteFeature($feature)
    {
        return $this->featureRepository->deleteFeature($feature);
    }

    public function selectFeatures(int $siteSettingId)
    {
        return $this->featureRepository->selectFeatures($siteSettingId);
    }
} 