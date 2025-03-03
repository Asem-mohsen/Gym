<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\{ AddUserRequest , UpdateUserRequest};
use App\Models\User;
use App\Services\{UserService , RoleService, SiteSettingService};
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected UserService $userService , protected RoleService $roleService, protected SiteSettingService $siteSettingService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index()
    {
        $users = $this->userService->getUsers($this->siteSettingId);
        return view('admin.users.index',compact('users'));
    }

    public function trainers()
    {
        $trainers = $this->userService->getTrainers($this->siteSettingId);
        return view('admin.users.index',compact('trainers'));
    }

    public function edit(Request $request , User $user)
    {
        $roles = $this->roleService->getRoles(where: ['name' => 'System User'] , siteSettingId: $this->siteSettingId);
        return view('admin.users.edit',get_defined_vars());
    }

    public function show(Request $request , User $user)
    {
        return view('admin.users.show',compact('user'));
    }

    public function store(AddUserRequest $request)
    {
        try {
            $this->userService->createUser($request->validated(), $this->siteSettingId);
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating new user, please try again in a few minutes.');
        }
    }

    public function update(UpdateUserRequest $request , User $user)
    {
        try {
            $user = $this->userService->updateUser($user,$request->validated() ,$this->siteSettingId);
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating user, please try again in a few minutes.');
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting user, please try again in a few minutes.');
        }
    }
}
