<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\{AddRoleRequest,UpdateRoleRequest};
use App\Models\Role;
use App\Services\RoleService;
use Exception;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    protected $rolesService;

    public function __construct(RoleService $roleService)
    {
        $this->rolesService = $roleService;
    }

    public function index()
    {
        $roles = $this->rolesService->getRoles(withCount: ['users']);
        return view('admin.roles.index',compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(AddRoleRequest $request)
    {
        try {
            $this->rolesService->createRole($request->validated());
            return redirect()->route('roles.index')->with('success', 'Role created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating a new role, please try again in a few minutes.');
        }
    }

    public function edit(Role $role)
    {
        $role = $this->rolesService->showRole($role);
        return view('admin.roles.edit',compact('role'));
    }

    public function update(UpdateRoleRequest $request , Role $role)
    {
        try {
            $this->rolesService->updateRole($role,$request->validated());
            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating the role, please try again in a few minutes.');
        }
    }

    public function destroy(Role $role)
    {
        try {
            $this->rolesService->deleteRole($role);
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error happened while deleting role, please try again in a few minutes.');
        }
    }

}
