<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\{ AddAdminRequest , UpdateAdminRequest};
use App\Models\User;
use App\Services\{ AdminService , RoleService, SiteSettingService};
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(protected AdminService $adminService ,protected RoleService $roleService , protected SiteSettingService $siteSettingService)
    {
        $this->adminService = $adminService;
        $this->roleService  = $roleService;
        $this->siteSettingService = $siteSettingService;
    }

    public function index(Request $request)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $admins = $this->adminService->getAdmins($siteSettingId, $perPage, $search);
        return view('admin.admins.index',compact('admins'));
    }

    public function create()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $roles = $this->roleService->getRoles(where: ['name' => 'admin'], siteSettingId: $siteSettingId);
        return view('admin.admins.create',compact('roles'));
    }

    public function store(AddAdminRequest $request)
    {
        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
            $this->adminService->createAdmin($request->validated() , $siteSettingId);
            return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating a new admin, please try again in a few minutes.');
        }
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

    public function edit(User $admin)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $roles = $this->roleService->getRoles(where: ['name' => 'admin'], siteSettingId: $siteSettingId);
        return view('admin.admins.edit', get_defined_vars());
    }

    public function show(User $admin)
    {
        return view('admin.admins.show', get_defined_vars());
    }

    public function destroy(User $admin)
    {
        try {
            $this->adminService->deleteAdmin($admin);
            return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error happened while deleting admin, please try again in a few minutes.');
        }
    }
}
