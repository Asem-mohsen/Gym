<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\{AddServiceRequest , UpdateServiceRequest};
use App\Models\Service;
use App\Services\{SiteSettingService , ServiceService, BranchService};
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected $siteSettingId;
    public function __construct(
        protected ServiceService $serviceService, 
        protected SiteSettingService $siteSettingService,
        protected BranchService $branchService
    ) {
        $this->serviceService = $serviceService;
        $this->siteSettingService = $siteSettingService;
        $this->branchService = $branchService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        $branchId = $request->get('branch_id');
        
        $services = $this->serviceService->getServices($this->siteSettingId,$branchId);
        
        $branches = $this->branchService->getBranches(siteSettingId: $this->siteSettingId);
        
        return view('admin.services.index', compact('services', 'branches'));
    }

    public function create()
    {
        $branches = $this->branchService->getBranches($this->siteSettingId);
        return view('admin.services.create', compact('branches'));
    }

    public function store(AddServiceRequest $request)
    {
        try {
            $data = $request->validated();
            $data['site_setting_id'] = $this->siteSettingId;

            $this->serviceService->createService($data);
            return redirect()->route('services.index')->with('success', 'Service created successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error happened while creating service, please try again in a few minutes.');
        }
    }

    public function edit(Service $service)
    {
        $service = $this->serviceService->showService($service);
        $branches = $this->branchService->getBranches($this->siteSettingId);
        $serviceBranches = $service->branches->pluck('id')->toArray();
        $gallery = $this->serviceService->getServiceGallery($service);
        
        return view('admin.services.edit', compact('service', 'branches', 'serviceBranches', 'gallery'));
    }

    public function update(UpdateServiceRequest $request , Service $service)
    {
        try {
            $data = $request->validated();
            $service = $this->serviceService->updateService($service , $data);
            return redirect()->route('services.index')->with('success', 'Service updated successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error happened while updating service, please try again in a few minutes.');
        }
    }

    public function show(Service $service)
    {
        $service = $this->serviceService->showService($service);

        return view('admin.services.show', compact('service'));
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
