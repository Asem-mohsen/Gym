<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\SiteSettingResource;
use App\Services\SiteSettingService;
use App\Services\LocationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $gyms->each(function ($gym) use ($userLocation) {
            $gym->distance_info = $this->locationService->getGymDistanceInfo($gym, $userLocation);
            $gym->redirection_url = "/gym/{$gym->slug}";
        });

        return successResponse(SiteSettingResource::collection($gyms), 'Gyms retrieved successfully');
    }
}
