<?php 
namespace App\Repositories;

use App\Models\SiteSetting;

class SiteSettingRepository
{
    public function getSiteSettings()
    {
        return SiteSetting::with('branches.phones')->get();
    }

    public function createSiteSetting(array $data)
    {
        return SiteSetting::create($data);
    }

    public function updateSiteSetting(SiteSetting $siteSetting , array $data)
    {
        $siteSetting->update($data);
        return $siteSetting;
    }

    public function deleteSiteSetting(SiteSetting $siteSetting)
    {
        $siteSetting->delete();
    }

    public function findById(int $id): ?SiteSetting
    {
        return SiteSetting::with('branches.phones')->find($id);
    }

}