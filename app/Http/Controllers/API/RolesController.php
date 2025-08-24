<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\{ AddRoleRequest, UpdateRoleRequest};
use App\Models\Role;
use App\Services\{RoleService , SiteSettingService};
use Exception;

class RolesController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected RoleService $roleService, protected SiteSettingService $siteSettingService)
    {
        $this->roleService = $roleService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index()
    {
        try {
            $roles = $this->roleService->getRoles();
            return successResponse(compact('roles'), 'role data retrived successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving roles, please try again.');
        }
    }

    public function store(AddRoleRequest $request)
    {
        try {
            $roles = $this->roleService->createRole($request->validated());
            return successResponse(compact('roles'), 'role created successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating role, please try again.');
        }
    }

    public function edit(Role $role)
    {
        try {
            $role = $this->roleService->showRole($role);
            return successResponse(compact('role'), 'role data retrived successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving roles, please try again.');
        }
    }

    public function update(UpdateRoleRequest $request , Role $role)
    {
        try {
            $role = $this->roleService->updateRole($role,$request->validated());
            return successResponse(compact('role'), 'role updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error updating roles, please try again.');
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role = $this->roleService->deleteRole($role);
            return successResponse(compact('role'), 'role deleted successfully');
        } catch (Exception $e) {
            return failureResponse('Error deleting roles, please try again.');
        }
    }
}
