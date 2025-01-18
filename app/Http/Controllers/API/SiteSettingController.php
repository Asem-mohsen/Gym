<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteSettings\{AddSiteSettingRequest, UpdateSiteSettingRequest};
use App\Models\SiteSetting;
use App\Services\SiteSettingService;
use Exception;

class SiteSettingController extends Controller
{
    protected $siteSettingService;

    public function __construct(SiteSettingService $siteSettingService)
    {
        $this->$siteSettingService = $siteSettingService;
    }

    public function index()
    {
        try{
            $siteSettings = $this->siteSettingService->getAllSiteSettings();
            successResponse(compact('siteSettings'));
        }catch(Exception $e){
            return failureResponse('Error retrieving site, please try again.');
        }
    }

    public function store(AddSiteSettingRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $siteSettingData = [
                'name' => $validatedData['name'],
                'size' => $validatedData['size'],
            ];

            $branchesData = $validatedData['branches'] ?? [];

            $newSiteSettings = $this->siteSettingService->createSiteSetting($siteSettingData , $branchesData);
            return successResponse(compact('newSiteSettings'), 'New Site added successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while creating a new site, please try again in a few minutes');
        }
    }

    public function update(UpdateSiteSettingRequest $request, SiteSetting $siteSetting)
    {
        try {
            $updatedSite = $this->siteSettingService->updateSiteSetting($siteSetting, $request->validated());
            return successResponse(compact('updatedSite'), 'Site updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while updating site, please try again in a few minutes');
        }
    }

    public function edit(SiteSetting $siteSetting)
    {
        try {
            $siteSetting = $this->siteSettingService->getSiteSettingById($siteSetting->id);
            return successResponse(compact('siteSetting'), 'Site retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while retrieving site, please try again in a few minutes');
        }
    }

    public function destroy(SiteSetting $siteSetting)
    {
        try {
            $this->siteSettingService->deleteSiteSetting($siteSetting);
            return successResponse(message: 'Site deleted successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while deleting site, please try again in a few minutes');
        }
    }

}
