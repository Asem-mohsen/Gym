<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\{ AddUserRequest , UpdateUserRequest};
use App\Models\User;
use App\Services\{UserService , RoleService, SiteSettingService, BranchService, RoleAssignmentService};
use Exception;
use Illuminate\Http\Request;
class StaffController extends Controller
{
    protected int $siteSettingId;

    public function __construct(
        protected UserService $userService, 
        protected RoleService $roleService, 
        protected SiteSettingService $siteSettingService, 
        protected BranchService $branchService, 
        protected RoleAssignmentService $roleAssignmentService
    ) {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->branchService = $branchService;
        $this->siteSettingService = $siteSettingService;
        $this->roleAssignmentService = $roleAssignmentService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        try {
            $staff = $this->userService->getStaff($this->siteSettingId,$request->get('branch_id'));
            
            $branches = $this->branchService->getBranches($this->siteSettingId);
            
            foreach ($staff as $member) {
                $member->has_set_password = $member->hasSetPassword();
            }
            
            return view('admin.staff.index', compact('staff', 'branches'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while fetching staff, please try again in a few minutes.');
        }
    }

    public function create()
    {
        $roles = $this->roleAssignmentService->getRoles(['sales', 'management']);

        $staffRoles = collect($roles)->filter(function($role) {
            return !in_array($role['name'], ['admin', 'regular_user', 'trainer']);
        })->toArray();
        
        return view('admin.staff.create', compact('staffRoles'));
    }

    public function edit(Request $request, User $staff)
    {
        if ($staff->hasRole(['admin', 'regular_user', 'trainer'])) {
            return redirect()->back()->with('error', 'Selected user is not a staff member.');
        }

        $roles = $this->roleAssignmentService->getRoles(['sales', 'management']);

        $staffRoles = collect($roles)->filter(function($role) {
            return !in_array($role['name'], ['admin', 'regular_user', 'trainer']);
        })->toArray();
        
        return view('admin.staff.edit', compact('staff', 'staffRoles'));
    }

    public function show(Request $request, User $staff)
    {
        if ($staff->hasRole(['admin', 'regular_user', 'trainer'])) {
            return redirect()->back()->with('error', 'Selected user is not a staff member.');
        }

        $staff->load(['photos' => function($query) {
            $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }]);

        return view('admin.staff.show', compact('staff'));
    }

    public function store(AddUserRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            $this->userService->createAdminUser($data, $this->siteSettingId);
            return redirect()->route('staff.index')->with('success', 'Staff member created successfully. An onboarding email has been sent to set their password.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating new staff member, please try again in a few minutes.');
        }
    }

    public function update(UpdateUserRequest $request, User $staff)
    {
        try {
            if ($staff->hasRole(['admin', 'regular_user', 'trainer'])) {
                return redirect()->back()->with('error', 'Selected user is not a staff member.');
            }

            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            $this->userService->updateUser($staff, $data, $this->siteSettingId);
            return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating staff member, please try again in a few minutes.');
        }
    }

    public function destroy(User $staff)
    {
        try {
            // Ensure the user is actually staff
            if ($staff->hasRole(['admin', 'regular_user'])) {
                return redirect()->back()->with('error', 'Selected user is not a staff member.');
            }

            // Get the site setting for the staff member
            $site = $staff->getCurrentSite();
            
            $this->userService->deleteUser($staff, $site);
            return redirect()->back()->with('success', 'Staff member deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting staff member, please try again in a few minutes.');
        }
    }

    public function resendOnboardingEmail(User $staff)
    {
        try {
            if ($staff->hasRole(['admin', 'regular_user', 'trainer'])) {
                return redirect()->back()->with('error', 'Selected user is not a staff member.');
            }

            if ($staff->hasSetPassword()) {
                return redirect()->back()->with('error', 'This user has already set their password. Onboarding email is not needed.');
            }

            $this->userService->sendOnboardingEmail($staff, $this->siteSettingId);
            return redirect()->back()->with('success', 'Onboarding email has been resent successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while resending onboarding email, please try again in a few minutes.');
        }
    }
}
