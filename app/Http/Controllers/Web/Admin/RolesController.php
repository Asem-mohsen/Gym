<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\{AddRoleRequest,UpdateRoleRequest};
use App\Models\Role;
use App\Services\{RoleService , SiteSettingService};
use Exception;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected RoleService $rolesService, protected SiteSettingService $siteSettingService)
    {
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index()
    {
        $roles = $this->rolesService->getRoles(withCount: ['users']);
        return view('admin.roles.index',compact('roles'));
    }
}
