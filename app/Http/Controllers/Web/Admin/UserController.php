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
        $perPage = $request->get('per_page', 15);
        $branchId = $request->get('branch_id');
        $search = $request->get('search');
        
        $users = $this->userService->getUsers($this->siteSettingId, $perPage, $branchId, $search);
        
        $branches = $this->branchService->getBranches($this->siteSettingId);
        
        return view('admin.users.index', compact('users', 'branches'));
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
        $roles = $this->roleService->getRolesForSelect(siteSettingId: $this->siteSettingId);
        return view('admin.users.create', compact('roles'));
    }

    public function edit(Request $request, User $user)
    {
        $roles = $this->roleService->getRolesForSelect(siteSettingId: $this->siteSettingId);
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
            return redirect()->back()->with('success', 'User created successfully.');
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
            $this->userService->deleteUser($user);
            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting user, please try again in a few minutes.');
        }
    }
   
}
