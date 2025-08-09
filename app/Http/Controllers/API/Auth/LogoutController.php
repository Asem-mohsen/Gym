<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LogoutService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    protected $logoutService;

    public function __construct(LogoutService $logoutService)
    {
        $this->logoutService = $logoutService;
    }

    /**
     * Logout from the current session.
     */
    public function logoutFromCurrentSession(Request $request)
    {
        try {
            $this->logoutService->logoutFromCurrentSession($request);
            return successResponse(message: 'Successfully logged out from the current session');
        } catch (\Exception $e) {
            return failureResponse($e->getMessage(), 500);
        }
    }

    /**
     * Logout from all sessions.
     */
    public function logoutFromAllSessions(Request $request)
    {
        try {
            $this->logoutService->logoutFromAllSessions($request);
            return successResponse(message: 'Successfully logged out from all sessions');
        } catch (\Exception $e) {
            return failureResponse($e->getMessage(), 500);
        }
    }

    /**
     * Logout from all other sessions except the current one.
     */
    public function logoutFromOtherSessions(Request $request)
    {
        try {
            $this->logoutService->logoutFromOtherSessions($request);
            return successResponse(message: 'Successfully logged out from all other sessions except this one');
        } catch (\Exception $e) {
            return failureResponse($e->getMessage(), 500);
        }
    }
}
