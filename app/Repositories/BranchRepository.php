<?php 
namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class BranchRepository
{
    /**
     * Get all branches with their phones.
     */
    public function getBranches()
    {
        return Branch::with('phones')->get();
    }

    /**
     * Create a new branch with phones.
     */
    public function createBranch(array $branchData, array $phonesData = [])
    {
        return DB::transaction(function () use ($branchData, $phonesData) {
            $branch = Branch::create($branchData);

            if (!empty($phonesData)) {
                $branch->phones()->createMany($phonesData);
            }

            return $branch->load('phones');
        });
    }

    /**
     * Update a branch and its phones.
     */
    public function updateBranch(Branch $branch, array $branchData, array $phonesData = [])
    {
        return DB::transaction(function () use ($branch, $branchData, $phonesData) {
            $branch->update($branchData);

            // Replace phones if provided
            if (!empty($phonesData)) {
                $branch->phones()->delete();
                $branch->phones()->createMany($phonesData);
            }

            return $branch->load('phones');
        });
    }

    /**
     * Delete a branch and its phones.
     */
    public function deleteBranch(Branch $branch)
    {
        DB::transaction(function () use ($branch) {
            $branch->phones()->delete();
            $branch->delete();
        });
    }

    /**
     * Find a branch by ID with its phones.
     */
    public function findById(int $id)
    {
        return Branch::with('phones')->find($id);
    }

}