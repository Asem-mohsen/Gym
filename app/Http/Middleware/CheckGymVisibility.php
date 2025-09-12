<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiteSetting;

class CheckGymVisibility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $gym = $request->route('siteSetting') ?? $request->route('gym');
        
        if ($gym && $gym instanceof SiteSetting) {
            if (!$gym->is_website_visible) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => 'This gym is not available for now. Please select another gym.',
                        'error' => 'gym_not_available'
                    ], 403);
                }
                
                return redirect()->route('gym.selection')
                    ->with('error', 'This gym is not available for now. Please select another gym.');
            }
        }
        
        return $next($request);
    }
}
