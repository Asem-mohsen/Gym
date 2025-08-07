<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Services\{SiteSettingService , ServiceService , MembershipService};

class ServicesController extends Controller
{
    public function __construct(protected ServiceService $serviceService, protected SiteSettingService $siteSettingService, protected MembershipService $membershipService)
    {
        $this->serviceService = $serviceService;
        $this->siteSettingService = $siteSettingService;
        $this->membershipService = $membershipService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $services = $this->serviceService->getServices($siteSettingId);
        $memberships = $this->membershipService->getMemberships($siteSettingId);

        return view('user.services',compact('services','memberships'));
    }

}
