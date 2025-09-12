<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Services\GymContextService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StoreGymContext
{
    public function __construct(private GymContextService $gymContextService)
    {
        $this->gymContextService = $gymContextService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $siteSetting = $request->route('siteSetting');
        
        if ($siteSetting && is_object($siteSetting)) {
            // Store gym context using the service
            $this->gymContextService->storeGymContext(
                $siteSetting->id,
                $siteSetting->slug,
                $siteSetting->gym_name,
                $siteSetting->getFirstMediaUrl('gym_logo')
            );
            
            // Store gym context in cache for API users
            if (Auth::guard('sanctum')->check()) {
                $this->gymContextService->storeGymContextForApi(
                    $request->user()->id,
                    $siteSetting->id
                );
            }
        }
        
        return $next($request);
    }
}
