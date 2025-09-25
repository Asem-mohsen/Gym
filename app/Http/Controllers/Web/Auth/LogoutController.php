<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\Auth\LogoutService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(protected LogoutService $logoutService)
    {
        $this->logoutService = $logoutService;
    }

    public function logoutFromCurrentSession(Request $request, SiteSetting $siteSetting)
    {
        $this->logoutService->logoutFromCurrentSession($request);
        
        if($siteSetting) {
            return redirect()->route('auth.login.index', ['siteSetting' => $siteSetting->slug])->with('success', 'You\'ve been logged out from this session.');
        }

        return redirect()->route('gym.selection')->with('success', 'You\'ve been logged out from this session.');
    }

    public function logoutFromAllSessions(Request $request, SiteSetting $siteSetting)
    {
        $this->logoutService->logoutFromAllSessions($request);
        
        if($siteSetting) {
            return redirect()->route('auth.login.index', ['siteSetting' => $siteSetting->slug])->with('success', 'Logged out from all sessions.');
        }

        return redirect()->route('gym.selection')->with('success', 'Logged out from all sessions.');
    }

    public function logoutFromOtherSessions(Request $request, SiteSetting $siteSetting)
    {
        $this->logoutService->logoutFromOtherSessions($request);

        if($siteSetting) {
            return redirect()->route('auth.login.index', ['siteSetting' => $siteSetting->slug])->with('success', 'Logged out from other sessions.');
        }

        return redirect()->route('gym.selection')->with('success', 'Logged out from other sessions.');
    }
}
