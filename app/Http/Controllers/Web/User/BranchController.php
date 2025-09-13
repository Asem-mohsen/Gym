<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Branch;
use App\Services\BranchService;

class BranchController extends Controller
{
    public function __construct(
        protected BranchService $branchService
    ) {}

    /**
     * Display a listing of branches.
     */
    public function index(SiteSetting $siteSetting)
    {
        $branches = $this->branchService->getBranchesForPublic($siteSetting->id);
        
        return view('user.branches.index', compact('branches'));
    }

    /**
     * Display the specified branch.
     */
    public function show(SiteSetting $siteSetting, Branch $branch)
    {
        if ($branch->site_setting_id !== $siteSetting->id) {
            abort(404);
        }

        $branch = $this->branchService->getBranchForPublic($branch->id);

        return view('user.branches.show', compact('branch'));
    }
}