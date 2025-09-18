<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\{Service, SiteSetting};
use App\Services\{ServiceService , MembershipService};

class ServicesController extends Controller
{
    public function __construct(protected ServiceService $serviceService, protected MembershipService $membershipService)
    {
        $this->serviceService = $serviceService;
        $this->membershipService = $membershipService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $services = $this->serviceService->getAvailableServices($siteSetting->id);
        $memberships = $this->membershipService->getMemberships($siteSetting->id);

        return view('user.services.index', compact('services', 'memberships', 'siteSetting'));
    }

    public function show(SiteSetting $siteSetting, Service $service)
    {
        $service->load([
            'branches',
            'galleries.media'
        ]);

        return view('user.services.show', compact('service', 'siteSetting'));
    }
}
