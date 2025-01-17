<?php 
namespace App\Services;

use App\Repositories\ServiceRepository;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getServices()
    {
        return $this->serviceRepository->getAllService();
    }

    public function createService(array $data)
    {
        return $this->serviceRepository->createService($data);
    }

    public function updateService($service, array $data)
    {
        return $this->serviceRepository->updateService($service, $data);
    }

    public function showService($service)
    {
        return $this->serviceRepository->findById($service->id);
    }

    public function deleteService($service)
    {
        return $this->serviceRepository->deleteService($service);
    }
}