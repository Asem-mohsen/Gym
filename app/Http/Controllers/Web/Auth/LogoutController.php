<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logoutFromCurrentSession(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login.index')->with('success', 'You’ve been logged out from this session.');
    }

    public function logoutFromAllSessions(Request $request)
    {
        // Invalidate all other sessions for the user
        Auth::logoutOtherDevices($request->input('password'));

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login.index')->with('success', 'Logged out from all sessions.');
    }

    public function logoutFromOtherSessions(Request $request)
    {
        Auth::logoutOtherDevices($request->input('password'));

        return back()->with('success', 'Logged out from other sessions.');
    }
}
