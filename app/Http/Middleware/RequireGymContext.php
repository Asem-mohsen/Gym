<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\GymContextService;
use Symfony\Component\HttpFoundation\Response;

class RequireGymContext
{
    public function __construct(private GymContextService $gymContextService)
    {
        $this->gymContextService = $gymContextService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $gymContext = $this->gymContextService->getCurrentGymContext();
        
        if (!$gymContext) {
            return redirect()->route('gym.selection');
        }
        
        view()->share('gymContext', $gymContext);
        
        return $next($request);
    }
}
