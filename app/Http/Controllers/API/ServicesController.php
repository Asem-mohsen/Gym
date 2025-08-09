<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\{AddServiceRequest , UpdateServiceRequest};
use App\Models\Service;
use App\Models\SiteSetting;
use App\Services\ServiceService;
use Exception;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index()
    {
        try {
            // This method is for admin routes, so we need to get site_setting_id from authenticated user
            // For now, we'll require it to be passed in the request
            $siteSettingId = request()->input('site_setting_id');
            
            if (!$siteSettingId) {
                return failureResponse('site_setting_id is required', 400);
            }

            $services = $this->serviceService->getServices($siteSettingId);
            return successResponse(compact('services'), 'Service data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving services, please try again.');
        }
    }

    public function services(SiteSetting $gym)
    {
        try {
            $services = $this->serviceService->getServices($gym->id);
            return successResponse(compact('services'), 'Services data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving services, please try again.');
        }
    }

    public function store(AddServiceRequest $request)
    {
        try {
            $services = $this->serviceService->createService($request->validated());
            return successResponse(compact('services'), 'Service data created successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating services, please try again.');
        }
    }

    public function edit(Service $service)
    {
        try {
            $service = $this->serviceService->showService($service);
            return successResponse(compact('service'), 'Service data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving services, please try again.');
        }
    }

    public function update(UpdateServiceRequest $request , Service $service)
    {
        try {
            $service = $this->serviceService->updateService($service , $request->validated());
            return successResponse(compact('service'), 'Service data updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error updating services, please try again.');
        }
    }

    public function destroy(Service $service)
    {
        try {
            $this->serviceService->deleteService($service);
            return successResponse(message: 'Service data deleted successfully');
        } catch (Exception $e) {
            return failureResponse('Error deleting services, please try again.');
        }
    }
}
