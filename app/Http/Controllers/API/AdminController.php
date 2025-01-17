<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\{ AddAdminRequest , UpdateAdminRequest};
use App\Models\{ User, Role};
use App\Services\AdminService;
use Exception;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
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
        $roles = Role::all();
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
        $roles = Role::all();
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
