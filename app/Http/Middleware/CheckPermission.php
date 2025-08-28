<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\GymPermissionService;
use App\Services\SiteSettingService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(
        protected GymPermissionService $gymPermissionService,
        protected SiteSettingService $siteSettingService
    ) {}

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        /**
         * @var User $user
         */
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('auth.login.index');
        }

        // Admin has all permissions
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        if ($user->hasPermissionTo($permission)) {
            return $next($request);
        }

        try {
            $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
            if ($user->hasGymPermission($permission, $siteSettingId)) {
                return $next($request);
            }
        } catch (Exception $e) {
            // If there's an error getting site setting, fall back to global permissions only
        }

        abort(403, 'Access denied. You do not have permission to access this resource.');
    }
}
