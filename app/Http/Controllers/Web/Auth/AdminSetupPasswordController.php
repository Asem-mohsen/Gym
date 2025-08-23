<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\OnBoarding\AdminOnboardingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminSetupPasswordController extends Controller
{
    public function __construct(protected AdminOnboardingService $adminOnboardingService) {}

    public function showSetupForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('auth.login.index')->withErrors(['email' => 'Invalid setup link.']);
        }

        // Verify the token
        if (!$this->adminOnboardingService->verifyOnboardingToken($token, $email)) {
            return redirect()->route('auth.login.index')->withErrors(['email' => 'Invalid or expired setup link.']);
        }

        return view('auth.admin-setup-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function setupPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => [
                'required', 'confirmed',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()
            ]
        ]);

        $status = $this->adminOnboardingService->completeOnboarding(
            $request->email,
            $request->token,
            $request->password
        );

        return match ($status) {
            'invalid' => back()->withErrors(['token' => 'Invalid or expired setup link.']),
            'user_not_found' => back()->withErrors(['email' => 'User not found.']),
            'success' => redirect()->route('auth.login.index')->with('success', 'Password set successfully. You can now log in.'),
        };
    }
}
