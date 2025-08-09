<?php

namespace App\Http\Controllers\Web\Auth;

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

    public function logoutFromCurrentSession(Request $request)
    {
        $this->logoutService->logoutFromCurrentSession($request);
        return redirect()->route('auth.login.index')->with('success', 'You\'ve been logged out from this session.');
    }

    public function logoutFromAllSessions(Request $request)
    {
        $this->logoutService->logoutFromAllSessions($request);
        return redirect()->route('auth.login.index')->with('success', 'Logged out from all sessions.');
    }

    public function logoutFromOtherSessions(Request $request)
    {
        $this->logoutService->logoutFromOtherSessions($request);
        return back()->with('success', 'Logged out from other sessions.');
    }
}
