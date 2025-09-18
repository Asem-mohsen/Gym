<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\{Service, SiteSetting};
use App\Services\ServiceService;
use Exception;

class ServicesController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(SiteSetting $gym)
    {
        try {
            $services = $this->serviceService->getServices($gym->id);
            
            $bookingTypes = $services->pluck('booking_type')->unique()->values()->toArray();
            
            $data = [
                'services' => ServiceResource::collection($services),
                'booking_types' => $bookingTypes,
            ];
            
            return successResponse($data, 'Services data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving services, please try again.');
        }
    }

    public function show(SiteSetting $gym, Service $service)
    {
        try {
            if ($service->site_setting_id != $gym->id) {
                return failureResponse('Invalid service or gym', 400);
            }
            
            $service = $this->serviceService->showService($service);
            
            return successResponse(new ServiceResource($service), 'Service data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving service, please try again.');
        }
    }
}
