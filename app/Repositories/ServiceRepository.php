<?php 
namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    public function getAllService()
    {
        return Service::all();
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
        return Service::find($id);
    }
}