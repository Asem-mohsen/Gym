<?php 
namespace App\Services;

use App\Repositories\BranchRepository;

class BranchService
{
    public function __construct(protected BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getBranches(int $siteSettingId)
    {
        return $this->branchRepository->getBranches($siteSettingId, withSubscriptionCount: true);
    }

    public function createBranch(array $branchData , array $phonesData , int $siteId)
    {
        return $this->branchRepository->createBranch($branchData, $siteId, $phonesData);
    }

    public function updateBranch($branch, array $branchData, array $phonesData)
    {
        return $this->branchRepository->updateBranch($branch, $branchData, $phonesData);
    }

    public function showBranch($branch)
    {
        return $this->branchRepository->findById($branch->id);
    }

    public function deleteBranch($branch)
    {
        return $this->branchRepository->deleteBranch($branch);
    }
}