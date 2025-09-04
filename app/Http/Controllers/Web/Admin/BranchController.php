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

            $branchData = Arr::except($validated, ['phones']);
            $phonesData = $validated['phones'];

            $siteId = Auth::user()?->site->id;

            $this->branchService->createBranch($branchData , $phonesData , $siteId);
            return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating a new branch, please try again in a few minutes.');
        }
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        try {
            $validated = $request->validated();
            $branchData = Arr::except($validated, ['phones']);
            $phonesData = $validated['phones'] ?? [];
    
            $this->branchService->updateBranch($branch, $branchData, $phonesData);
    
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
}
