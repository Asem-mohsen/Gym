<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LogoutService;
use App\Services\GymContextService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    protected $logoutService;
    protected $gymContextService;

    public function __construct(LogoutService $logoutService, GymContextService $gymContextService)
    {
        $this->logoutService = $logoutService;
        $this->gymContextService = $gymContextService;
    }

    public function logoutFromCurrentSession(Request $request)
    {
        $this->logoutService->logoutFromCurrentSession($request);
        
        $gymContext = $this->gymContextService->getCurrentGymContext();
        return redirect()->route('auth.login.index', ['siteSetting' => $gymContext['slug']])->with('success', 'You\'ve been logged out from this session.');
    }

    public function logoutFromAllSessions(Request $request)
    {
        $this->logoutService->logoutFromAllSessions($request);
        
        $gymContext = $this->gymContextService->getCurrentGymContext();
        return redirect()->route('auth.login.index', ['siteSetting' => $gymContext['slug']])->with('success', 'Logged out from all sessions.');
    }

    public function logoutFromOtherSessions(Request $request)
    {
        $this->logoutService->logoutFromOtherSessions($request);
        return back()->with('success', 'Logged out from other sessions.');
    }
}
