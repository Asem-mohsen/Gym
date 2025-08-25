<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\{ AddUserRequest , UpdateUserRequest};
use App\Models\User;
use App\Services\{UserService , RoleService, SiteSettingService, BranchService};
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected UserService $userService , protected RoleService $roleService, protected SiteSettingService $siteSettingService, protected BranchService $branchService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->branchService = $branchService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
            $users = $this->userService->getUsers(
                $siteSettingId,
                $request->get('per_page', 15),
                $request->get('branch_id'),
                $request->get('search')
            );
            
            // Get branches for filter
            $branches = $this->branchService->getBranches($siteSettingId);
            
            // Add password status to each user
            foreach ($users as $user) {
                $user->has_set_password = $this->userService->hasUserSetPassword($user);
            }
            
            return view('admin.users.index', compact('users', 'branches'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while fetching users, please try again in a few minutes.');
        }
    }

    public function trainers(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $branchId = $request->get('branch_id');
        $search = $request->get('search');
        
        $users = $this->userService->getTrainers($this->siteSettingId, $perPage, $branchId, $search);
        
        $branches = $this->branchService->getBranches($this->siteSettingId);
        
        return view('admin.users.index', compact('users', 'branches'));
    }

    public function create()
    {
        $roles = $this->roleService->getRolesForUserCreation();
        return view('admin.users.create', compact('roles'));
    }

    public function edit(Request $request, User $user)
    {
        $roles = $this->roleService->getAllRolesForAdmin();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function show(Request $request , User $user)
    {
        return view('admin.users.show',compact('user'));
    }

    public function store(AddUserRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            $this->userService->createUser($data, $this->siteSettingId);
            return redirect()->back()->with('success', 'User created successfully. An onboarding email has been sent to set their password.');
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
            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting user, please try again in a few minutes.');
        }
    }
   
    public function resendOnboardingEmail(User $user)
    {
        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
            $this->userService->sendOnboardingEmail($user, $siteSettingId);
            
            return redirect()->back()->with('success', 'Onboarding email has been resent successfully.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while resending onboarding email, please try again in a few minutes.');
        }
    }
}
