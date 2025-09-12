<?php

namespace App\Http\Controllers\Web\User;

use Exception;
use Illuminate\Support\Facades\Log;
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
        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingIdOrFallback();
            $siteSetting = $this->siteSettingService->getSiteSettingById($siteSettingId);
            $trainers = $this->userService->getTrainers(siteSettingId: $siteSettingId);

            return view('user.404', compact('trainers', 'siteSetting'));
        } catch (Exception $e) {
            // Log the error for debugging
            Log::warning('404 page error: ' . $e->getMessage());
            
            // If no site settings are available, show a basic 404 page
            return view('user.404', [
                'trainers' => collect(),
                'siteSetting' => null
            ]);
        }
    }
}
