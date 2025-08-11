<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingService;

class GymSelectionController extends Controller
{
    public function __construct(protected SiteSettingService $siteSettingService)
    {
        $this->siteSettingService = $siteSettingService;
    }

    public function index()
    {
        $gyms = $this->siteSettingService->getAllSiteSettings(['branches.phones', 'media', 'services', 'classes']);

        $gyms = $gyms->sortBy(function($gym) {
            return $gym->getTranslation('gym_name', app()->getLocale());
        });

        return view('user.gym-selection', compact('gyms'));
    }
}
