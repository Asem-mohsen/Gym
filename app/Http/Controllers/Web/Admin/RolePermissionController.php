<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RoleAssignmentService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Exception;

class RolePermissionController extends Controller
{
    public function __construct(
        protected RoleAssignmentService $roleAssignmentService
    ) {
        $this->roleAssignmentService = $roleAssignmentService;
    }

    /**
     * Show roles management page
     */
    public function index()
    {
        try {
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();
            
            return view('admin.roles.index', compact('roles', 'permissions'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading roles and permissions.');
        }
    }

    /**
     * Show role details and permissions
     */
    public function show(Role $role)
    {
        try {
            $role->load('permissions');
            $allPermissions = Permission::all();
            $usersWithRole = User::role($role)->get();
            
            return view('admin.roles.show', compact('role', 'allPermissions', 'usersWithRole'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading role details.');
        }
    }

    /**
     * Show user permissions management
     */
    public function userPermissions(User $user)
    {
        try {
            $user->load('roles', 'permissions');
            $allRoles = Role::all();
            $allPermissions = Permission::all();
            
            return view('admin.roles.user-permissions', compact('user', 'allRoles', 'allPermissions'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading user permissions.');
        }
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        try {
            $request->validate([
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $permissionIds = $request->input('permissions', []);
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            
            $role->syncPermissions($permissions);
            
            return redirect()->back()->with('success', 'Role permissions updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating role permissions.');
        }
    }

    /**
     * Update user roles
     */
    public function updateUserRoles(Request $request, User $user)
    {
        try {
            $request->validate([
                'roles' => 'array',
                'roles.*' => 'exists:roles,id'
            ]);

            $roleIds = $request->input('roles', []);
            $roles = Role::whereIn('id', $roleIds)->get();
            
            $user->syncRoles($roles);
            
            return redirect()->back()->with('success', 'User roles updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating user roles.');
        }
    }

    /**
     * Update user direct permissions
     */
    public function updateUserPermissions(Request $request, User $user)
    {
        try {
            $request->validate([
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $permissionIds = $request->input('permissions', []);
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            
            $user->syncPermissions($permissions);
            
            return redirect()->back()->with('success', 'User permissions updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating user permissions.');
        }
    }

    /**
     * Create new role
     */
    public function createRole(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $role = Role::create(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
            
            return redirect()->back()->with('success', 'Role created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error creating role.');
        }
    }

    /**
     * Delete role
     */
    public function deleteRole(Role $role)
    {
        try {
            // Don't allow deletion of system roles
            if (in_array($role->name, ['admin', 'regular_user'])) {
                return redirect()->back()->with('error', 'Cannot delete system roles.');
            }
            
            $role->delete();
            
            return redirect()->back()->with('success', 'Role deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error deleting role.');
        }
    }

    /**
     * Get users by role for AJAX requests
     */
    public function getUsersByRole(Role $role)
    {
        try {
            $users = User::role($role)->with('roles')->get();
            
            return response()->json($users);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error loading users'], 500);
        }
    }
}
