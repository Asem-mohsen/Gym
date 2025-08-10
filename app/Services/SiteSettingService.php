<?php 
namespace App\Services;

use App\Models\SiteSetting;
use App\Repositories\BranchRepository;
use App\Repositories\SiteSettingRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiteSettingService 
{
    public function __construct(protected SiteSettingRepository $siteSettingRepository, protected BranchRepository $branchRepository)
    {
        $this->siteSettingRepository = $siteSettingRepository;
        $this->branchRepository = $branchRepository;
    }

    public function getCurrentSiteSettingId(): ?int
    {
        return Auth::check() ? Auth::user()->site_setting_id : null;
    }

    public function createSiteSetting(array $siteSettingData, array $branchesData)
    {
        return DB::transaction(function () use ($siteSettingData, $branchesData) {
            $siteSetting = $this->siteSettingRepository->createSiteSetting($siteSettingData);

            foreach ($branchesData as $branchData) {
                $phones = $branchData['phones'] ?? [];
                unset($branchData['phones']);

                $branchData['site_setting_id'] = $siteSetting->id;
                $this->branchRepository->createBranch($branchData, $phones , $siteSetting->id);
            }

            return $siteSetting->load('branches.phones');
        });
    }

    /**
     * Get all site settings.
     */
    public function getAllSiteSettings($with = [])
    {
        return $this->siteSettingRepository->getSiteSettings($with);
    }

    /**
     * Update an existing site setting with branches
     */
    public function updateSiteSettingWithBranches(SiteSetting $siteSetting, array $siteSettingData, array $branchesData)
    {
        return DB::transaction(function () use ($siteSetting, $siteSettingData, $branchesData) {

            $this->siteSettingRepository->updateSiteSetting($siteSetting, $siteSettingData);

            $siteSetting->branches()->delete();
            foreach ($branchesData as $branchData) {
                $phones = $branchData['phones'] ?? [];
                unset($branchData['phones']);

                $branchData['site_setting_id'] = $siteSetting->id;
                $this->branchRepository->createBranch($branchData, $phones , $siteSetting->id);
            }

            return $siteSetting->load('branches.phones');
        });
    }

    /**
     * Find a site setting by ID.
     */
    public function getSiteSettingById(int $id)
    {
        $siteSetting = $this->siteSettingRepository->findById($id);

        if (!$siteSetting) {
            throw new \Exception("Site setting not found.", 404);
        }

        return $siteSetting;
    }

    /**
     * Update a site setting (basic, no branches).
     */
    public function updateSiteSetting(SiteSetting $siteSetting, array $data)
    {
        return $this->siteSettingRepository->updateSiteSetting($siteSetting, $data);
    }
}