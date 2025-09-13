<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branches\{AddBranchRequest , UpdateBranchRequest};
use App\Models\Branch;
use App\Services\{AdminService, BranchService, SiteSettingService};
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Auth, Log};

class BranchController extends Controller
{
    public function __construct(protected AdminService $adminService ,protected BranchService $branchService, protected SiteSettingService $siteSettingService)
    {
        $this->adminService = $adminService;
        $this->branchService  = $branchService;
        $this->siteSettingService = $siteSettingService;
    }

    public function index()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();

        $branches = $this->branchService->getBranches($siteSettingId);
        return view('admin.branches.index',compact('branches'));
    }

    public function create()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();

        $users = $this->adminService->getAvailableAdminsForReassignment($siteSettingId, 0); // 0 means no exclusion
        return view('admin.branches.create',compact('users'));
    }

    public function store(AddBranchRequest $request)
    {
        try {
            $validated = $request->validated();

            $branchData = Arr::except($validated, ['phones', 'main_image', 'gallery_images', 'opening_hours']);
            $phonesData = $validated['phones'];
            $openingHoursData = $this->prepareOpeningHoursData($validated['opening_hours'] ?? []);
            
            $galleryData = [
                'main_image' => $request->file('main_image'),
                'gallery_images' => $request->file('gallery_images', []),
            ];
            
            $branchData['is_visible'] = $request->has('is_visible');

            $siteId = Auth::user()?->site->id;

            $this->branchService->createBranch($branchData , $phonesData , $siteId, $galleryData, $openingHoursData);
            return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating a new branch, please try again in a few minutes.');
        }
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        try {
            $validated = $request->validated();
            $branchData = Arr::except($validated, ['phones', 'main_image', 'gallery_images', 'opening_hours']);
            $phonesData = $validated['phones'] ?? [];
            $openingHoursData = $this->prepareOpeningHoursData($validated['opening_hours'] ?? []);
            
            $galleryData = [
                'main_image' => $request->file('main_image'),
                'gallery_images' => $request->file('gallery_images', []),
            ];
            
            $branchData['is_visible'] = $request->has('is_visible');
    
            $this->branchService->updateBranch($branch, $branchData, $phonesData, $galleryData, $openingHoursData);
    
            return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating branch', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error happened while updating branch, please try again in a few minutes.');
        }
    }

    public function edit(Branch $branch)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $users = $this->adminService->getAvailableAdminsForReassignment($siteSettingId, 0); // 0 means no exclusion
        $branch = $this->branchService->showBranch($branch);
        $existingPhones = $branch->phones->pluck('phone_number')->toArray();

        return view('admin.branches.edit', get_defined_vars());
    }

    public function show(Branch $branch)
    {
        $branch = $this->branchService->showBranch($branch);
        return view('admin.branches.show', compact('branch'));
    }

    public function destroy(Branch $branch)
    {
        try {
            $this->branchService->deleteBranch($branch);
            return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error happened while deleting branch, please try again in a few minutes.');
        }
    }

    /**
     * Prepare opening hours data for storage
     */
    private function prepareOpeningHoursData(array $openingHoursData): array
    {
        $preparedData = [];
        
        foreach ($openingHoursData as $index => $hoursData) {
            if (!empty($hoursData['days']) && is_array($hoursData['days'])) {
                // Handle is_closed - if not present, default to false
                $isClosed = false;
                if (isset($hoursData['is_closed'])) {
                    $isClosed = in_array($hoursData['is_closed'], ['1', 1, true, 'true'], true);
                }
                
                $preparedData[] = [
                    'days' => $hoursData['days'],
                    'opening_time' => $isClosed ? null : ($hoursData['opening_time'] ?? null),
                    'closing_time' => $isClosed ? null : ($hoursData['closing_time'] ?? null),
                    'is_closed' => $isClosed,
                ];
            }
        }
        
        return $preparedData;
    }
}
