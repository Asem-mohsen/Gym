<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\SiteSettingService;
use Exception;

class SiteSettingController extends Controller
{
    protected $siteSettingService;

    public function __construct(SiteSettingService $siteSettingService)
    {
        $this->siteSettingService = $siteSettingService;
    }

    public function index(SiteSetting $gym)
    {
        try{
            $siteSetting = $this->siteSettingService->getSiteSettingById($gym->id);
            return successResponse(compact('siteSetting') , 'Site setting retrieved successfully');
        }catch(Exception $e){
            return failureResponse('Error retrieving site, please try again.');
        }
    }
}
