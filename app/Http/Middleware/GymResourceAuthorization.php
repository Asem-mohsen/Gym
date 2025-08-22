<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GymResourceAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $siteSetting = $request->route()->parameter('siteSetting');
        
        if (!$siteSetting) {
            return $next($request);
        }

        // Check if the route has a resource parameter that should belong to this gym
        $resourceTypes = [
            'membership' => 'site_setting_id',
            'class' => 'site_setting_id', 
            'gallery' => 'site_setting_id',
            'branch' => 'site_setting_id',
        ];

        foreach ($resourceTypes as $resourceName => $foreignKey) {
            $resource = $request->route()->parameter($resourceName);
            
            if ($resource && method_exists($resource, 'getAttribute')) {
                $resourceGymId = $resource->getAttribute($foreignKey);
                
                if ($resourceGymId !== $siteSetting->id) {
                    abort(404, ucfirst($resourceName) . ' not found in this gym.');
                }
            }
        }

        return $next($request);
    }
}
