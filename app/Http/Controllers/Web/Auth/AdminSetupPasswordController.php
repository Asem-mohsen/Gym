<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\OnBoarding\AdminOnboardingService;
use App\Services\GymContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminSetupPasswordController extends Controller
{
    public function __construct(
        protected AdminOnboardingService $adminOnboardingService,
        protected GymContextService $gymContextService
    ) {}

    public function showSetupForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');
        
        $this->gymContextService->setGymContextFromUrl($request->path());
        
        $gymContext = $this->gymContextService->getCurrentGymContext();

        if (!$token || !$email) {
            return redirect()->route('auth.login.index', ['siteSetting' => $gymContext['slug']])->withErrors(['email' => 'Invalid setup link.']);
        }

        if (!$this->adminOnboardingService->verifyOnboardingToken($token, $email)) {
            return redirect()->route('auth.login.index', ['siteSetting' => $gymContext['slug']])->withErrors(['email' => 'Invalid or expired setup link.']);
        }

        return view('auth.admin-setup-password', [
            'token' => $token,
            'email' => $email,
            'gymContext' => $gymContext,
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

        $gymContext = $this->gymContextService->getCurrentGymContext();

        return match ($status) {
            'invalid' => back()->withErrors(['token' => 'Invalid or expired setup link.']),
            'user_not_found' => back()->withErrors(['email' => 'User not found.']),
            'success' => redirect()->route('auth.login.index', ['siteSetting' => $gymContext['slug']])->with('success', 'Password set successfully. You can now log in.'),
        };
    }
}
