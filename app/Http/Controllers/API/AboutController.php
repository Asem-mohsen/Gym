<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use Exception;

class AboutController extends Controller
{
    public function __construct(protected SiteSettingService $siteSettingService)
    {
        $this->siteSettingService = $siteSettingService;
    }

    public function aboutUs(SiteSetting $gym)
    {
        try {
            $data = [
                'about_us' => $gym->about_us,
                'mission' => $gym->mission,
                'vision' => $gym->vision,
                'values' => $gym->values,
            ];

            return successResponse($data, 'About us data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving about us data, please try again.');
        }
    }
}
