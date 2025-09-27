<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\{ AddUserRequest , UpdateUserRequest};
use App\Models\User;
use App\Services\{UserService , RoleService, SiteSettingService, BranchService, RoleAssignmentService};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected UserService $userService , protected RoleService $roleService, protected SiteSettingService $siteSettingService, protected BranchService $branchService, protected RoleAssignmentService $roleAssignmentService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->branchService = $branchService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $this->roleAssignmentService = $roleAssignmentService;
    }

    public function index(Request $request)
    {
        try {
            $users = $this->userService->getUsers($this->siteSettingId);
            
            return view('admin.users.index', compact('users'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while fetching users, please try again in a few minutes.');
        }
    }

    public function create()
    {
        $roles = $this->roleAssignmentService->getRoles(['regular_user']);
        return view('admin.users.create', compact('roles'));
    }

    public function edit(Request $request, User $user)
    {
        $user = $this->userService->showUser($user , ['roles']);
        $selectedRoles = $user->roles->pluck('id')->toArray();
        
        $roles = $this->roleAssignmentService->getRoles(['regular_user','trainer','sales','management']);
        return view('admin.users.edit', compact('user', 'roles', 'selectedRoles'));
    }

    public function show(Request $request , User $user)
    {
        $user->load(['photos' => function($query) {
            $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }]);
        return view('admin.users.show',compact('user'));
    }

    public function store(AddUserRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            $this->userService->createAdminUser($data, $this->siteSettingId);
            return redirect()->route('users.index')->with('success', 'User created successfully. An onboarding email has been sent to set their password.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating new user, please try again in a few minutes.');
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            $user = $this->userService->updateUser($user, $data, $this->siteSettingId);
            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating user, please try again in a few minutes.');
        }
    }

    public function destroy(User $user)
    {
        try {
            $siteSetting = $this->siteSettingService->getSiteSettingById($this->siteSettingId);
            $this->userService->deleteUser($user , $siteSetting);
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting user, please try again in a few minutes.');
        }
    }
   
    public function resendOnboardingEmail(User $user)
    {
        try {
            if ($user->hasSetPassword()) {
                return redirect()->back()->with('error', 'This user has already set their password. Onboarding email is not needed.');
            }

            $this->userService->sendOnboardingEmail($user, $this->siteSettingId);
            
            return redirect()->back()->with('success', 'Onboarding email has been resent successfully.');

        } catch (Exception $e) {
            Log::error('Error happened while resending onboarding email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error happened while resending onboarding email, please try again in a few minutes.');
        }
    }
}
