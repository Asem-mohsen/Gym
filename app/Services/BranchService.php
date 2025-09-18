<?php 
namespace App\Services;

use App\Repositories\BranchRepository;
use App\Repositories\GalleryRepository;

class BranchService
{
    public function __construct(
        protected BranchRepository $branchRepository,
        protected GalleryRepository $galleryRepository
    ) {
        $this->branchRepository = $branchRepository;
        $this->galleryRepository = $galleryRepository;
    }

    public function getBranches(int $siteSettingId)
    {
        return $this->branchRepository->getBranches($siteSettingId, withSubscriptionCount: true);
    }

    public function createBranch(array $branchData , array $phonesData , int $siteId, array $galleryData = [], array $openingHoursData = [])
    {
        return $this->branchRepository->createBranch($branchData, $siteId, $phonesData, $galleryData, $openingHoursData);
    }

    public function updateBranch($branch, array $branchData, array $phonesData, array $galleryData = [], array $openingHoursData = [])
    {
        return $this->branchRepository->updateBranch($branch, $branchData, $phonesData, $galleryData, $openingHoursData);
    }

    public function showBranch($branch)
    {
        return $this->branchRepository->findById($branch->id);
    }

    public function deleteBranch($branch)
    {
        return $this->branchRepository->deleteBranch($branch);
    }

    public function getBranchesForPublic(int $siteSettingId)
    {
        return $this->branchRepository->getBranchesForPublic($siteSettingId);
    }

    public function getBranchForPublic(int $branchId)
    {
        return $this->branchRepository->getBranchForPublic($branchId);
    }
}