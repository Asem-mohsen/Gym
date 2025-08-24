<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingService;
use App\Services\LocationService;
use Illuminate\Http\Request;

class GymSelectionController extends Controller
{
    public function __construct(
        protected SiteSettingService $siteSettingService,
        protected LocationService $locationService
    ) {
        $this->siteSettingService = $siteSettingService;
        $this->locationService = $locationService;
    }

    public function index(Request $request)
    {
        $gyms = $this->siteSettingService->getAllSiteSettings(['branches.phones', 'media', 'services', 'classes']);

        $userLocation = $this->locationService->getUserLocation($request);

        $gyms = $this->locationService->sortGymsByScoreAndLocation($gyms, $userLocation);

        return view('user.gym-selection', compact('gyms', 'userLocation'));
    }
}
