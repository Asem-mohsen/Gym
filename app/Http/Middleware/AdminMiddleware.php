<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function __construct(protected AuthService $authService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('sanctum')->user();

        if ($user && $user->role->name === "Admin") {
            return $next($request);
        }

        if ($user) {
            $this->authService->handleUnauthorizedAccess($user);
        }

        return to_route('auth.login.index')->with('error', 'Unauthorized access. Your account has been disabled.');
    }
}
