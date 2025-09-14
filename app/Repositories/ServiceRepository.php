<?php 
namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    public function getAllService(int $siteSettingId, ?int $branchId)
    {
        $query = Service::where('site_setting_id', $siteSettingId)
            ->with('branches')
            ->when($branchId, function($query) use ($branchId){
                $query->whereHas('branches', function($query)use ($branchId) {
                    $query->where('branch_id',$branchId)->where('is_visible', true);
                });
            });
        
        return $query->get();
    }

    public function getAvailableServices(int $siteSettingId)
    {
        return Service::where('site_setting_id', $siteSettingId)
                     ->available()
                     ->whereHas('branches', function($query) {
                         $query->where('is_visible', true);
                     })
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