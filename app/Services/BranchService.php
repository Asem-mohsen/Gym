<?php 
namespace App\Services;

use App\Repositories\BranchRepository;

class BranchService
{
    public function __construct(protected BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getBranches()
    {
        return $this->branchRepository->getBranches();
    }

    public function createBranch(array $branchData , array $phonesData , int $siteId)
    {
        return $this->branchRepository->createBranch($branchData, $phonesData , $siteId);
    }

    public function updateBranch($branch, array $phonesData, array $newData)
    {
        return $this->branchRepository->updateBranch($branch,$newData,$phonesData);
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