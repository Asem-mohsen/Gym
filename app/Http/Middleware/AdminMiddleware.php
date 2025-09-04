<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\GymContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function __construct(
        protected AuthService $authService,
        protected GymContextService $gymContextService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var User $user
         */

        $user = Auth::guard('web')->user();

        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        if ($user && $user->hasRole('regular_user')) {
            $this->authService->handleUnauthorizedAccess($user);
        }

        $gymContext = $this->gymContextService->getCurrentGymContext();
        
        return to_route('auth.login.index', ['siteSetting' => $gymContext['slug']])->with('error', 'Unauthorized access. Your account has been disabled.');
    }
}
