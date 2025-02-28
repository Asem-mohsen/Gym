<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\{ AddAdminRequest , UpdateAdminRequest};
use App\Models\User;
use App\Services\{ AdminService , RoleService};
use Exception;

class AdminController extends Controller
{
    protected $adminService , $roleService;

    public function __construct(AdminService $adminService , RoleService $roleService)
    {
        $this->adminService = $adminService;
        $this->roleService  = $roleService;
    }

    public function index()
    {
        try {
            $admins = $this->adminService->getAdmins();
            return successResponse(compact('admins'), 'Admins data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving admins, please try again.');
        }
    }

    public function create()
    {
        $roles = $this->roleService->getRoles(where: ['name' => 'admin']);
        return successResponse(compact('roles'), 'Roles for adding admins retrieved successfully');
    }

    public function store(AddAdminRequest $request)
    {
        try {
            $newAdmin = $this->adminService->createAdmin($request->validated());
            return successResponse(compact('newAdmin'), 'Admin added successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while creating a new admin, please try again in a few minutes');
        }
    }

    public function update(UpdateAdminRequest $request, User $user)
    {
        try {
            $updatedAdmin = $this->adminService->updateAdmin($user, $request->validated());
            return successResponse(compact('updatedAdmin'), 'Admin updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while updating admin, please try again in a few minutes');
        }
    }

    public function edit(User $user)
    {
        $roles = $this->roleService->getRoles();
        return successResponse(compact('user', 'roles'), 'Admin retrieved successfully');
    }

    public function destroy(User $user)
    {
        try {
            $this->adminService->deleteAdmin($user);
            return successResponse(message: 'Admin deleted successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while deleting admin, please try again in a few minutes');
        }
    }
}
