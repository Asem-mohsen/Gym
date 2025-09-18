<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\SiteSettingResource;
use App\Services\{SiteSettingService, LocationService};
use App\Http\Requests\GymSelections\GymSelectionRequest;
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

    public function index(GymSelectionRequest $request)
    {
        $validatedData = $request->validated();

        $gyms = $this->siteSettingService->getAllSiteSettings(['branches.phones', 'media', 'services', 'classes']);

        $userLocation = $this->locationService->getUserLocation($validatedData);

        $gyms = $this->sortGymsByPreference($gyms, $userLocation, $validatedData['sort_by'] ?? 'score_and_location');

        $includeDistance = $validatedData['include_distance'] ?? true;
        
        $gyms->each(function ($gym) use ($userLocation, $includeDistance) {
            if ($includeDistance) {
                $gym->distance_info = $this->locationService->getGymDistanceInfo($gym, $userLocation);
            }
            $gym->redirection_url = "/gym/{$gym->slug}";
        });

        $responseData = [
            'gyms' => SiteSettingResource::collection($gyms),
            'user_location' => $userLocation,
            'total_gyms' => $gyms->count(),
            'sorting_applied' => $validatedData['sort_by'] ?? 'score_and_location'
        ];

        return successResponse($responseData, 'Gyms retrieved successfully');
    }

    /**
     * Sort gyms based on user preference
     */
    private function sortGymsByPreference($gyms, $userLocation, string $sortBy)
    {
        switch ($sortBy) {
            case 'distance':
                return $this->locationService->sortGymsByDistance($gyms, $userLocation);
            case 'score':
                return $this->locationService->sortGymsByScoreOnly($gyms);
            case 'alphabetical':
                return $gyms->sortBy('name');
            case 'score_and_location':
            default:
                return $this->locationService->sortGymsByScoreAndLocation($gyms, $userLocation);
        }
    }
}
