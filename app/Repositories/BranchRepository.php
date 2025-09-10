<?php 
namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class BranchRepository
{
    /**
     * Get all branches with their phones.
     */
    public function getBranches(int $siteSettingId ,$withSubscriptionCount = false, $with = ['phones'])
    {
        $query = Branch::where('site_setting_id', $siteSettingId);
        
        if($with){
            $query->with($with);
        }

        if ($withSubscriptionCount) {
            $query->withCount('subscriptions');
        }

        return $query->get();
    }

    /**
     * Create a new branch with phones.
     */
    public function createBranch(array $branchData,int $siteId, array $phonesData = [])
    {
        return DB::transaction(function () use ($branchData, $siteId, $phonesData) {
            $branchData['site_setting_id'] = $siteId;
    
            $branch = Branch::create($branchData);
    
            if (!empty($phonesData)) {

                $formattedPhones = [];
                
                foreach ($phonesData as $phoneItem) {
                    if (is_array($phoneItem) && isset($phoneItem['phones']) && !empty(trim($phoneItem['phones']))) {
                        $formattedPhones[] = [
                            'phone_number' => trim($phoneItem['phones']),
                        ];
                    }
                }
                
                if (!empty($formattedPhones)) {
                    $branch->phones()->createMany($formattedPhones);
                }
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
                $branch->phones()->delete();
                
                $formattedPhones = [];
                
                foreach ($phonesData as $phoneItem) {
                    if (is_array($phoneItem) && isset($phoneItem['phones']) && !empty(trim($phoneItem['phones']))) {
                        $formattedPhones[] = [
                            'phone_number' => trim($phoneItem['phones']),
                        ];
                    }
                }
                
                if (!empty($formattedPhones)) {
                    $branch->phones()->createMany($formattedPhones);
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