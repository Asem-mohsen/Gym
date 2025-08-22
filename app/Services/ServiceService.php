<?php 
namespace App\Services;

use App\Repositories\{ServiceRepository, GalleryRepository};
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ServiceService
{
    protected $serviceRepository;
    protected $galleryRepository;

    public function __construct(ServiceRepository $serviceRepository, GalleryRepository $galleryRepository)
    {
        $this->serviceRepository = $serviceRepository;
        $this->galleryRepository = $galleryRepository;
    }

    public function getServices(int $siteSettingId)
    {
        return $this->serviceRepository->getAllService($siteSettingId);
    }

    public function getAvailableServices(int $siteSettingId)
    {
        return $this->serviceRepository->getAvailableServices($siteSettingId);
    }

    public function createService(array $data)
    {
        return DB::transaction(function () use ($data) {
            $service = $this->serviceRepository->createService($data);
            
            // Handle branch assignments
            if (isset($data['branches'])) {
                $this->assignBranchesToService($service, $data['branches']);
            }
            
            if (isset($data['image'])) {
                $service->addMedia($data['image'])->toMediaCollection('service_image');
            }

            // Handle gallery creation
            if (isset($data['gallery_title']) && !empty($data['gallery_title'])) {
                $this->createServiceGallery($service, $data);
            }
            
            return $service;
        });
    }

    public function updateService($service, array $data)
    {
        return DB::transaction(function () use ($service, $data) {
            $service = $this->serviceRepository->updateService($service, $data);
            
            // Handle branch assignments
            if (isset($data['branches'])) {
                $this->assignBranchesToService($service, $data['branches']);
            }
            
            if (isset($data['image'])) {
                $service->addMedia($data['image'])->toMediaCollection('service_image');
            }

            // Handle gallery updates
            if (isset($data['gallery_title']) && !empty($data['gallery_title'])) {
                $this->updateServiceGallery($service, $data);
            }
            
            return $service;
        });
    }

    public function showService($service)
    {
        return $this->serviceRepository->findById($service->id);
    }

    public function deleteService($service)
    {
        return $this->serviceRepository->deleteService($service);
    }

    public function getServicesWithBranches(int $siteSettingId)
    {
        return $this->serviceRepository->getServicesWithBranches($siteSettingId);
    }

    public function assignBranchesToService(Service $service, array $branchIds)
    {
        $service->branches()->detach();
        
        if (!empty($branchIds)) {
            $branchData = [];
            foreach ($branchIds as $branchId) {
                $branchData[$branchId] = [
                    'is_available' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $service->branches()->attach($branchData);
        }
    }

    public function createServiceGallery(Service $service, array $data)
    {
        $galleryData = [
            'title' => $data['gallery_title'],
            'description' => $data['gallery_description'] ?? null,
            'is_active' => true,
            'sort_order' => 1,
            'site_setting_id' => $service->site_setting_id,
        ];

        $gallery = $this->galleryRepository->createGallery($galleryData, $service);
        
        if (isset($data['gallery_images']) && is_array($data['gallery_images'])) {
            foreach ($data['gallery_images'] as $image) {
                if ($image && $image->isValid()) {
                    $this->galleryRepository->addMediaToGallery($gallery, $image);
                }
            }
        }
        
        return $gallery;
    }

    public function updateServiceGallery(Service $service, array $data)
    {
        $existingGallery = $service->galleries()->first();
        
        if ($existingGallery) {
            $galleryData = [
                'title' => $data['gallery_title'],
                'description' => $data['gallery_description'] ?? null,
                'site_setting_id' => $service->site_setting_id,
            ];
            
            $this->galleryRepository->updateGallery($existingGallery, $galleryData);
            
            if (isset($data['gallery_images']) && is_array($data['gallery_images'])) {
                foreach ($data['gallery_images'] as $image) {
                    if ($image && $image->isValid()) {
                        $this->galleryRepository->addMediaToGallery($existingGallery, $image);
                    }
                }
            }
            
            return $existingGallery;
        } else {
            return $this->createServiceGallery($service, $data);
        }
    }

    public function getServiceGallery(Service $service)
    {
        return $service->galleries()->with('media')->first();
    }
}