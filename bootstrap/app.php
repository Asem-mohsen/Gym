<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\ShareSiteSetting::class,
            \App\Http\Middleware\StoreGymContext::class,
            \App\Http\Middleware\GymResourceAuthorization::class,
        ]);
        $middleware->api(append: [
            \App\Http\Middleware\StoreGymContext::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'preventAuth' =>\App\Http\Middleware\PreventAuth::class,
            'auth' => \App\Http\Middleware\UserMiddleware::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'store.gym.context' => \App\Http\Middleware\StoreGymContext::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'share.site.setting' => \App\Http\Middleware\ShareSiteSetting::class,
            'require.gym.context' => \App\Http\Middleware\RequireGymContext::class,
            'gym.resource.auth' => \App\Http\Middleware\GymResourceAuthorization::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return new JsonResponse([
                    'message' => 'Too many requests. Please wait a moment before trying again.',
                    'error' => 'throttle_exceeded'
                ], 429);
            }
        });
    })->create();
