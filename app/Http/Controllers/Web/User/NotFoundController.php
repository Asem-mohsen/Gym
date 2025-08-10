<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingService;
use App\Services\UserService;

class NotFoundController extends Controller
{
    public function __construct(protected UserService $userService, protected SiteSettingService $siteSettingService)
    {
        $this->userService = $userService;
        $this->siteSettingService = $siteSettingService;
    }

    public function index()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $siteSetting = $this->siteSettingService->getSiteSettingById($siteSettingId);
        $trainers = $this->userService->getTrainers(siteSettingId: $siteSettingId);

        return view('user.404', compact('trainers', 'siteSetting'));
    }
}
