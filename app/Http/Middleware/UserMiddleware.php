<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GymContextService;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function __construct(protected GymContextService $gymContextService) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('sanctum')->check()) {
            return $next($request);
        }
        
        return redirect()->route('gym.selection');
    }
}
