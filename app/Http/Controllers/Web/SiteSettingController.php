<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteSettings\{AddSiteSettingRequest, UpdateSiteSettingRequest};
use App\Models\SiteSetting;
use App\Services\{SiteSettingService , AdminService};
use Exception;

class SiteSettingController extends Controller
{
    public function __construct(protected SiteSettingService $siteSettingService , protected AdminService $adminService)
    {
        $this->siteSettingService = $siteSettingService;
        $this->adminService = $adminService;
    }

    public function edit(SiteSetting $siteSetting)
    {
        $site = $this->siteSettingService->getSiteSettingById($siteSetting->id);
        $users = $this->adminService->getAdmins();
        return view('admin.site-settings.edit',get_defined_vars());
    }

    public function update(UpdateSiteSettingRequest $request, SiteSetting $siteSetting)
    {
        try {
            $data = $request->validated();
            $data['gym_logo'] = $request->file('gym_logo');
            $data['favicon'] = $request->file('favicon');
            $data['email_logo'] = $request->file('email_logo');
            $data['footer_logo'] = $request->file('footer_logo');

            $this->siteSettingService->updateSiteSetting($siteSetting, $data);

            return redirect()->route('site-settings.edit')->with('success', 'Settings updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating site settings, please try again in a few minutes.');
        }
    }
}
