<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->roles->name === "Admin"){
            return $next($request);
        }

        return failureResponse('unauthorized access' , 404);
    }
}
