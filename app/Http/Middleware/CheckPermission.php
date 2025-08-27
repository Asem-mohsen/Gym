<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
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

        // Check if user has the specific permission
        if (!$user->hasPermissionTo($permission)) {
            abort(403, 'Access denied. You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
