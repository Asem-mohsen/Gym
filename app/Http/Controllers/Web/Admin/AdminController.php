<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\{ AddAdminRequest , UpdateAdminRequest};
use App\Models\User;
use App\Services\{ AdminService , RoleAssignmentService, RoleService, SiteSettingService};
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(protected AdminService $adminService ,protected RoleService $roleService , protected SiteSettingService $siteSettingService , protected RoleAssignmentService $roleAssignmentService)
    {
        $this->adminService = $adminService;
        $this->roleService  = $roleService;
        $this->siteSettingService = $siteSettingService;
        $this->roleAssignmentService = $roleAssignmentService;
    }

    public function index(Request $request)
    {
        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
            $admins = $this->adminService->getAdmins(
                $siteSettingId,
                $request->get('per_page', 15),
                $request->get('search')
            );
            
            // Add password status to each admin
            foreach ($admins as $admin) {
                $admin->has_set_password = $this->adminService->hasAdminSetPassword($admin);
            }
            
            return view('admin.admins.index', compact('admins'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while fetching admins, please try again in a few minutes.');
        }
    }

    public function create()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $roles = $this->roleAssignmentService->getRoles(['admin']);
        return view('admin.admins.create', compact('roles'));
    }

    public function store(AddAdminRequest $request)
    {
        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
            $this->adminService->createAdmin($request->validated() , $siteSettingId);
            return redirect()->route('admins.index')->with('success', 'Admin created successfully. An onboarding email has been sent to set their password.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating a new admin, please try again in a few minutes.');
        }
    }

    public function edit(User $admin)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $roles = $this->roleAssignmentService->getRoles(['admin']);
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function show(User $admin)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $roles = $this->roleAssignmentService->getRoles(['admin']);
        $admin->load(['photos' => function($query) {
            $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }]);
        return view('admin.admins.show', compact('admin', 'roles'));
    }

    public function update(UpdateAdminRequest $request, User $admin)
    {
        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
            $this->adminService->updateAdmin($admin, $request->validated(), $siteSettingId);
            return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating admin, please try again in a few minutes.');
        }
    }

    public function destroy(User $admin)
    {
        try {
            if ($this->adminService->isAdminBranchManager($admin)) {
                $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
                $availableAdmins = $this->adminService->getAvailableAdminsForReassignment($siteSettingId, $admin->id);
                
                $availableAdminsWithImage = $availableAdmins->map(function($admin) {
                    $userImage = $admin->user_image;
                    return [
                        'id' => $admin->id,
                        'name' => $admin->name,
                        'email' => $admin->email,
                        'user_image' => $userImage
                    ];
                });
                
                return response()->json([
                    'success' => false,
                    'is_manager' => true,
                    'message' => 'This admin is a branch manager. Please select a new manager before deleting.',
                    'available_admins' => $availableAdminsWithImage
                ]);
            }
            
            $this->adminService->deleteAdmin($admin);

            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully.'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error happened while deleting admin, please try again in a few minutes.'
            ]);
        }
    }

    public function reassignManagerAndDelete(Request $request, User $admin)
    {
        try {
            $request->validate([
                'new_manager_id' => 'required|exists:users,id'
            ]);

            $newManager = User::findOrFail($request->new_manager_id);
            
            $this->adminService->reassignBranchManager($admin, $newManager);
            
            $this->adminService->deleteAdmin($admin);
            
            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully and branches reassigned to new manager.'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error happened while deleting admin, please try again in a few minutes.'
            ]);
        }
    }

    public function resendOnboardingEmail(User $admin)
    {
        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
            
            $this->adminService->sendOnboardingEmail($admin, $siteSettingId);
            
            return redirect()->back()->with('success', 'Onboarding email has been resent successfully.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while resending onboarding email, please try again in a few minutes.');
        }
    }
}
