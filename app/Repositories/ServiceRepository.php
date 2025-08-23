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

    public function getServicesWithBranches(int $siteSettingId, $perPage = 15, $search = null, $branchId = null)
    {
        $query = Service::where('site_setting_id', $siteSettingId)
                     ->with('branches')
                     ->ordered();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name->en', 'like', "%{$search}%")
                  ->orWhere('name->ar', 'like', "%{$search}%")
                  ->orWhere('description->en', 'like', "%{$search}%")
                  ->orWhere('description->ar', 'like', "%{$search}%");
            });
        }
        
        if ($branchId) {
            $query->whereHas('branches', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        
        return $query->paginate($perPage);
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