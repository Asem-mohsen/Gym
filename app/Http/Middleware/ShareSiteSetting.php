<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareSiteSetting
{
    public function handle(Request $request, Closure $next)
    {
        $siteSetting = $request->route('siteSetting');

        if ($siteSetting) {
            $siteSetting->load('branches.phones');
            view()->share('siteSetting', $siteSetting);
            
            $response = $next($request);
            
            if ($response instanceof \Illuminate\Http\Response) {
                $content = $response->getContent();
                
                $gymContextData = json_encode([
                    'id' => $siteSetting->id,
                    'slug' => $siteSetting->slug,
                    'name' => $siteSetting->gym_name,
                    'logo' => $siteSetting->getFirstMediaUrl('gym_logo'),
                ]);
                
                $bodyTag = '<body';
                $replacement = '<body data-gym-context=\'' . htmlspecialchars($gymContextData, ENT_QUOTES) . '\'';
                
                $content = str_replace($bodyTag, $replacement, $content);
                
                $response->setContent($content);
            }
            
            return $response;
        }
        
        return $next($request);
    }
}
