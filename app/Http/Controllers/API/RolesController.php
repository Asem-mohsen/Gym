<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\{ AddRoleRequest, UpdateRoleRequest};
use App\Models\Role;
use App\Services\RoleService;
use Exception;

class RolesController extends Controller
{
    protected $rolesService;

    public function __construct(RoleService $roleService)
    {
        $this->rolesService = $roleService;
    }

    public function index()
    {
        try {
            $roles = $this->rolesService->getRoles();
            return successResponse(compact('roles'), 'role data retrived successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving roles, please try again.');
        }
    }

    public function store(AddRoleRequest $request)
    {
        try {
            $roles = $this->rolesService->createRole($request->validated());
            return successResponse(compact('roles'), 'role created successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating role, please try again.');
        }
    }

    public function edit(Role $role)
    {
        try {
            $role = $this->rolesService->showRole($role);
            return successResponse(compact('role'), 'role data retrived successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving roles, please try again.');
        }
    }

    public function update(UpdateRoleRequest $request , Role $role)
    {
        try {
            $role = $this->rolesService->updateRole($role,$request->validated());
            return successResponse(compact('role'), 'role updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error updating roles, please try again.');
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role = $this->rolesService->deleteRole($role);
            return successResponse(compact('role'), 'role deleted successfully');
        } catch (Exception $e) {
            return failureResponse('Error deleting roles, please try again.');
        }
    }
}
