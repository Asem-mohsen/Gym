<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class AdminMiddleware
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->roleId === 1){
            return $next($request);
        }

        return $this->error(['error' => 'unauthorized access'], 'Unauthorized' , 404);
    }
}
