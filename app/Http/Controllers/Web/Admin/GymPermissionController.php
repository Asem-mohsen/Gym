<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GymPermissions\{
    AssignRolePermissionsRequest,
    AssignUserPermissionsRequest
};
use App\Models\{Role, User};
use App\Services\{GymPermissionService, SiteSettingService};
use Exception;
use Illuminate\Support\Facades\Log;

class GymPermissionController extends Controller
{
    protected $siteSettingId;
    public function __construct(
        protected GymPermissionService $gymPermissionService,
        protected SiteSettingService $siteSettingService
    ) {
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    /**
     * Show the permissions management dashboard
     */
    public function index()
    {
        try {
            $permissionGroups = $this->gymPermissionService->getPermissionGroups();
            
            return view('admin.permissions.index', compact('permissionGroups'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading permissions dashboard.');
        }
    }

    /**
     * Show role permissions management
     */
    public function rolePermissions()
    {
        try {
            $roles = Role::with(['permissions' => function($query) {
                $query->wherePivot('site_setting_id', $this->siteSettingId);
            }])->get();
            
            // Get user count for each role in this gym
            foreach ($roles as $role) {
                $role->users_count = User::whereHas('gyms', function($query) {
                    $query->where('site_setting_id', $this->siteSettingId);
                })->whereHas('roles', function($query) use ($role) {
                    $query->where('roles.id', $role->id);
                })->count();
            }
            
            $permissionGroups = $this->gymPermissionService->getPermissionGroups();
            
            return view('admin.permissions.role-permissions', compact('roles', 'permissionGroups'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading role permissions.');
        }
    }

    /**
     * Show user permissions management
     */
    public function userPermissions()
    {
        try {
            $users = User::whereHas('gyms', function($query) {
                $query->where('site_setting_id', $this->siteSettingId);
            })->with(['roles', 'permissions' => function($query) {
                $query->wherePivot('site_setting_id', $this->siteSettingId);
            }])->get();
            
            $permissionGroups = $this->gymPermissionService->getPermissionGroups();
            
            return view('admin.permissions.user-permissions', compact('users', 'permissionGroups'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading user permissions.');
        }
    }

    /**
     * Assign permissions to a role
     */
    public function assignRolePermissions(AssignRolePermissionsRequest $request, Role $role)
    {
        try {
            $this->gymPermissionService->assignPermissionsToRole($role, $request->permission_ids, $this->siteSettingId);
            
            return redirect()->back()->with('success', 'Role permissions updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating role permissions.');
        }
    }

    /**
     * Assign permissions to a user
     */
    public function assignUserPermissions(AssignUserPermissionsRequest $request, User $user)
    {
        try {
            // Log what permissions are being assigned
            Log::info('Assigning permissions to user', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'permissions' => $request->permission_ids,
                'site_setting_id' => $this->siteSettingId
            ]);
            
            $this->gymPermissionService->assignPermissionsToUser($user, $request->permission_ids, $this->siteSettingId);
            
            return redirect()->back()->with('success', 'User permissions updated successfully.');
        } catch (Exception $e) {
            Log::error('Error assigning user permissions', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error updating user permissions.');
        }
    }

    /**
     * Show specific role permissions
     */
    public function showRolePermissions(Role $role)
    {
        try {
            if (!auth()->user()->hasPermissionTo('manage_roles')) {
                return redirect()->back()->with('error', 'You do not have permission to view this role.');
            }
            
            $rolePermissions = $this->gymPermissionService->getRolePermissions($role, $this->siteSettingId);
            $permissionGroups = $this->gymPermissionService->getPermissionGroups();
            
            return view('admin.permissions.show-role-permissions', compact('role', 'rolePermissions', 'permissionGroups'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading role permissions.');
        }
    }

    /**
     * Show specific user permissions
     */
    public function showUserPermissions(User $user)
    {
        try {
            if (!auth()->user()->hasPermissionTo('manage_roles')) {
                return redirect()->back()->with('error', 'You do not have permission to view this role.');
            }

            $user->load(['roles.permissions' => function($query) {
                $query->wherePivot('site_setting_id', $this->siteSettingId);
            }, 'permissions' => function($query) {
                $query->wherePivot('site_setting_id', $this->siteSettingId);
            }]);

            $userPermissions = $this->gymPermissionService->getUserPermissions($user, $this->siteSettingId);
            $permissionGroups = $this->gymPermissionService->getPermissionGroups();
            
            // For display purposes, get all permissions the user can access
            $allUserPermissions = collect();
            
            // Add direct user permissions
            $allUserPermissions = $allUserPermissions->merge($userPermissions);
            
            // Add role-based permissions
            foreach ($user->roles as $role) {
                $rolePermissions = $this->gymPermissionService->getRolePermissions($role, $this->siteSettingId);
                $allUserPermissions = $allUserPermissions->merge($rolePermissions);
            }
            
            // Remove duplicates
            $allUserPermissions = $allUserPermissions->unique('name');
            
            return view('admin.permissions.show-user-permissions', compact('user', 'userPermissions', 'allUserPermissions', 'permissionGroups'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading user permissions.');
        }
    }
}
