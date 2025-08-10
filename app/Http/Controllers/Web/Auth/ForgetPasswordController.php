<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPassword\{ResetPasswordRequest, SendCodeRequest, VerifyCodeRequest};
use App\Services\Auth\ForgetPasswordService;
use App\Services\GymContextService;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function __construct(
        protected ForgetPasswordService $forgetPasswordService,
        protected GymContextService $gymContextService
    ) {}

    public function index()
    {
        $gymContext = $this->gymContextService->getCurrentGymContext();
        return view('auth.forgot-password', compact('gymContext'));
    }

    public function sendCode(SendCodeRequest $request)
    {
        $validated = $request->validated();

        $success = $this->forgetPasswordService->sendResetToken($validated['email']);

        $gymContext = $this->gymContextService->getCurrentGymContext();
        $redirectRoute = $gymContext ? 'auth.login.index' : 'auth.login.index';
        $routeParams = $gymContext ? ['siteSetting' => $gymContext['slug']] : [];
        
        return $success
            ? to_route($redirectRoute, $routeParams)->with('success', 'If the provided email is registered in our system, a password reset link has been sent to it.')
            : back()->withErrors(['email' => 'Email not found']);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $validated = $request->validated();

        $status = $this->forgetPasswordService->verifyToken($validated['token'], $validated['email']);

        if ($status === 'expired') {
            return back()->withErrors(['token' => 'Token expired. A new email has been sent.']);
        }

        if (!$status) {
            return back()->withErrors(['token' => 'Invalid token.']);
        }

        return redirect()->route('auth.forget-password.reset-form', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $status = $this->forgetPasswordService->resetPassword($validated['email'], $validated['token'], $validated['password']);

        $gymContext = $this->gymContextService->getCurrentGymContext();
        $redirectRoute = $gymContext ? 'auth.login.index' : 'auth.login.index';
        $routeParams = $gymContext ? ['siteSetting' => $gymContext['slug']] : [];
        
        return match ($status) {
            'expired' => back()->withErrors(['token' => 'Token expired. We sent you a new one.']),
            'invalid' => back()->withErrors(['token' => 'Invalid token.']),
            'success' => to_route($redirectRoute, $routeParams)->with('success', 'Password successfully reset.'),
        };
    }

    public function resetForm(Request $request)
    {
        $gymContext = $this->gymContextService->getCurrentGymContext();
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
            'gymContext' => $gymContext,
        ]);
    }
}
