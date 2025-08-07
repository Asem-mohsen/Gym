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
        }

        return $next($request);
    }
}
