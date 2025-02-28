<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\{AddServiceRequest , UpdateServiceRequest};
use App\Models\Service;
use App\Services\ServiceService;
use Exception;

class ServicesController extends Controller
{
    public function __construct(protected ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index()
    {
        $services = $this->serviceService->getServices();
        return view('admin.services.index',compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(AddServiceRequest $request)
    {
        try {
            $this->serviceService->createService($request->validated());
            return redirect()->route('services.index')->with('success', 'Service created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating service, please try again in a few minutes.');
        }
    }

    public function edit(Service $service)
    {
        $service = $this->serviceService->showService($service);
        return view('admin.services.edit',compact('service'));
    }

    public function update(UpdateServiceRequest $request , Service $service)
    {
        try {
            $service = $this->serviceService->updateService($service , $request->validated());
            return redirect()->route('services.index')->with('success', 'Service updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating service, please try again in a few minutes.');
        }
    }

    public function destroy(Service $service)
    {
        try {
            $this->serviceService->deleteService($service);
            return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('services.index')->with('success', 'Error deleting services, please try again..');
        }
    }
}
