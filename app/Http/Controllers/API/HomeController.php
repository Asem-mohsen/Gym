<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\{SiteSettingService , MembershipService};
use App\Services\UserService;

class HomeController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected MembershipService $membershipService ,protected UserService $userService, protected SiteSettingService $siteSettingService)
    {
        $this->membershipService = $membershipService;
        $this->userService = $userService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }
    
    public function index()
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $this->siteSettingId);
        $trainers    = $this->userService->getTrainers($this->siteSettingId);

        $data = [
            'memberships'=>$memberships,
            'trainers'   =>$trainers
        ];

        return successResponse($data, 'Home data retrieved');
    }
}
