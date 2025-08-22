<?php 
namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    public function getAllService(int $siteSettingId)
    {
        return Service::where('site_setting_id', $siteSettingId)->get();
    }

    public function getAvailableServices(int $siteSettingId)
    {
        return Service::where('site_setting_id', $siteSettingId)
                     ->available()
                     ->ordered()
                     ->get();
    }

    public function getServicesWithBranches(int $siteSettingId)
    {
        return Service::where('site_setting_id', $siteSettingId)
                     ->with('branches')
                     ->ordered()
                     ->get();
    }

    public function createService(array $data)
    {
        return Service::create($data);
    }

    public function updateService(Service $service , array $data)
    {
        $service->update($data);
        return $service;
    }

    public function deleteService(Service $service)
    {
        $service->delete();
    }

    public function findById(int $id): ?Service
    {
        return Service::with('branches', 'galleries')->find($id);
    }

    public function selectServices(int $siteSettingId)
    {
        return Service::where('site_setting_id', $siteSettingId)->select('id', 'name')->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->getTranslation('name', app()->getLocale()),
            ];
        });
    }
}