<?php 
namespace App\Services;

use App\Models\SiteSetting;
use App\Repositories\BranchRepository;
use App\Repositories\SiteSettingRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SiteSettingService 
{
    public function __construct(protected SiteSettingRepository $siteSettingRepository, protected BranchRepository $branchRepository)
    {
        $this->siteSettingRepository = $siteSettingRepository;
        $this->branchRepository = $branchRepository;
    }

    public function getCurrentSiteSettingId(): ?int
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        
        return Auth::check() ? $user->gyms()->first()->id : null;
    }

    public function createSiteSetting(array $siteSettingData, array $branchesData)
    {
        return DB::transaction(function () use ($siteSettingData, $branchesData) {
            $siteSetting = $this->siteSettingRepository->createSiteSetting($siteSettingData);

            foreach ($branchesData as $branchData) {
                $phones = $branchData['phones'] ?? [];
                unset($branchData['phones']);

                $branchData['site_setting_id'] = $siteSetting->id;
                $this->branchRepository->createBranch($branchData, $siteSetting->id, $phones);
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
                $this->branchRepository->createBranch($branchData, $siteSetting->id, $phones);
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

    /**
     * Get a site setting by slug.
     */
    public function getSiteSettingBySlug(string $slug)
    {
        return $this->siteSettingRepository->getSiteSettings()->where('slug', $slug)->first();
    }

    /**
     * Get the first available site setting as a fallback.
     */
    public function getFirstSiteSetting()
    {
        return $this->siteSettingRepository->getSiteSettings()->first();
    }

    /**
     * Get the default site setting (first available or by specific slug).
     */
    public function getDefaultSiteSetting()
    {
        $defaultSiteSetting = $this->getSiteSettingBySlug('nitro');
        
        if (!$defaultSiteSetting) {
            $defaultSiteSetting = $this->getFirstSiteSetting();
        }
        
        return $defaultSiteSetting;
    }

    /**
     * Get current site setting ID or fallback to default.
     */
    public function getCurrentSiteSettingIdOrFallback(): int
    {
        $siteSettingId = $this->getCurrentSiteSettingId();
        
        if ($siteSettingId === null) {
            $defaultSiteSetting = $this->getDefaultSiteSetting();
            if ($defaultSiteSetting) {
                return $defaultSiteSetting->id;
            }
            throw new \Exception("No site settings available.", 404);
        }
        
        return $siteSettingId;
    }
}