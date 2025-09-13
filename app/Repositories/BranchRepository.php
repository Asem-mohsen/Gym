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
     * Create a new branch with phones and gallery.
     */
    public function createBranch(array $branchData,int $siteId, array $phonesData = [], array $galleryData = [], array $openingHoursData = [])
    {
        return DB::transaction(function () use ($branchData, $siteId, $phonesData, $galleryData, $openingHoursData) {
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

            if (!empty($galleryData)) {
                $this->handleBranchGallery($branch, $galleryData);
            }

            if (!empty($openingHoursData)) {
                $this->handleBranchOpeningHours($branch, $openingHoursData);
            }
    
            return $branch->load(['phones', 'galleries.media', 'openingHours']);
        });
    }

    /**
     * Update a branch and its phones and gallery.
     */
    public function updateBranch(Branch $branch, array $branchData, array $phonesData = [], array $galleryData = [], array $openingHoursData = [])
    {
        return DB::transaction(function () use ($branch, $branchData, $phonesData, $galleryData, $openingHoursData) {

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

            if (!empty($galleryData)) {
                $this->handleBranchGallery($branch, $galleryData);
            }

            if (!empty($openingHoursData)) {
                $this->handleBranchOpeningHours($branch, $openingHoursData);
            }
    
            return $branch->load(['phones', 'galleries.media', 'openingHours']);
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
    public function findById(int $branchId)
    {
        return Branch::with(['phones'])->find($branchId);
    }

    /**
     * Get branches for public display with all necessary relationships.
     */
    public function getBranchesForPublic(int $siteSettingId)
    {
        return Branch::where('site_setting_id', $siteSettingId)
            ->with([
                'phones',
                'galleries.media',
                'services',
                'classes',
                'trainers',
                'openingHours'
            ])
            ->where('is_visible', true)
            ->get();
    }

    /**
     * Get a single branch for public display with all necessary relationships.
     */
    public function getBranchForPublic(int $branchId)
    {
        return Branch::with([
            'phones',
            'galleries.media',
            'services.media',
            'classes.media',
            'trainers',
            'manager',
            'openingHours'
        ])
        ->where('is_visible', true)
        ->find($branchId);
    }

    /**
     * Handle branch gallery creation/update
     */
    private function handleBranchGallery(Branch $branch, array $galleryData)
    {
        // Handle main image
        if (isset($galleryData['main_image']) && $galleryData['main_image']) {

            $branch->clearMediaCollection('branch_images');
            
            $branch->addMedia($galleryData['main_image'])->toMediaCollection('branch_images');
        }

        // Handle gallery images
        if (isset($galleryData['gallery_images']) && !empty($galleryData['gallery_images'])) {
            // Create or get existing gallery
            $gallery = $branch->galleries()->firstOrCreate([
                'title' => $branch->name . ' Gallery',
                'is_active' => true,
                'sort_order' => 0,
            ], [
                'site_setting_id' => $branch->site_setting_id,
            ]);

            // Add gallery images
            foreach ($galleryData['gallery_images'] as $image) {
                if ($image) {
                    $gallery->addMedia($image)
                        ->toMediaCollection('gallery_images');
                }
            }
        }
    }

    /**
     * Handle branch opening hours creation/update.
     */
    private function handleBranchOpeningHours(Branch $branch, array $openingHoursData)
    {
        $branch->openingHours()->delete();

        foreach ($openingHoursData as $hoursData) {
            if (!empty($hoursData['days']) && is_array($hoursData['days'])) {
                $isClosed = false;
                if (isset($hoursData['is_closed'])) {
                    $isClosed = in_array($hoursData['is_closed'], ['1', 1, true, 'true'], true);
                }
                
                foreach ($hoursData['days'] as $day) {
                    $branch->openingHours()->create([
                        'day_of_week' => $day,
                        'opening_time' => $isClosed ? null : ($hoursData['opening_time'] ?? null),
                        'closing_time' => $isClosed ? null : ($hoursData['closing_time'] ?? null),
                        'is_closed' => $isClosed,
                    ]);
                }
            }
        }
    }

}