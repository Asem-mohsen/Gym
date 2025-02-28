<?php 
namespace App\Repositories;

use App\Models\Branch;
use App\Models\Phone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function createBranch(array $branchData, array $phonesData = [], int $siteId)
    {
        return DB::transaction(function () use ($branchData, $phonesData, $siteId) {
            $branchData['site_setting_id'] = $siteId;
    
            $branch = Branch::create($branchData);
    
            if (!empty($phonesData)) {
                $formattedPhones = collect($phonesData)->map(fn($phone) => [
                    'phone_number' => $phone['phones'],
                ])->toArray();
    
                $branch->phones()->createMany($formattedPhones);
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
    
            if (!empty($phonesData)) {
                $existingPhoneIds = $branch->phones->pluck('id')->toArray();
                $newPhoneNumbers = collect($phonesData)->pluck('phone_number')->toArray();
    
                $branch->phones()->whereNotIn('phone_number', $newPhoneNumbers)->delete();
    
                foreach ($phonesData as $phone) {
                    $branch->phones()->updateOrCreate(
                        ['phone_number' => $phone['phone_number']], 
                        ['phone_number' => $phone['phone_number']]
                    );
                }
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