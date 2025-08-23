<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteSettings\UpdateSiteSettingRequest;
use App\Services\{SiteSettingService , AdminService};
use Exception;

class SiteSettingController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected SiteSettingService $siteSettingService , protected AdminService $adminService)
    {
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $this->siteSettingService = $siteSettingService;
        $this->adminService = $adminService;
    }

    public function edit()
    {
        $site = $this->siteSettingService->getSiteSettingById($this->siteSettingId);
        $users = $this->adminService->getAdmins($this->siteSettingId);
        return view('admin.site-settings.edit',get_defined_vars());
    }

    public function update(UpdateSiteSettingRequest $request)
    {
        try {
            $site = $this->siteSettingService->getSiteSettingById($this->siteSettingId);
            $data = $request->validated();
            $data['gym_logo'] = $request->file('gym_logo');
            $data['favicon'] = $request->file('favicon');
            $data['email_logo'] = $request->file('email_logo');
            $data['footer_logo'] = $request->file('footer_logo');

            $this->siteSettingService->updateSiteSetting($site, $data);

            return redirect()->route('site-settings.edit')->with('success', 'Settings updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating site settings, please try again in a few minutes.');
        }
    }
}
