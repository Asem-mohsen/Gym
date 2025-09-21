<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPassword\{ResetPasswordRequest, SendCodeRequest, VerifyCodeRequest};
use App\Models\SiteSetting;
use App\Services\Auth\ForgetPasswordService;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function __construct(
        protected ForgetPasswordService $forgetPasswordService
    ) {}

    public function index(SiteSetting $siteSetting)
    {
        return view('auth.forgot-password', compact('siteSetting'));
    }

    public function sendCode(SendCodeRequest $request, SiteSetting $siteSetting)
    {
        $validated = $request->validated();

        $success = $this->forgetPasswordService->sendResetToken($validated['email'], 'token');

        return $success
            ? to_route('auth.login.index', ['siteSetting' => $siteSetting->slug])->with('success', 'If the provided email is registered in our system, a password reset link has been sent to it.')
            : back()->withErrors(['email' => 'Email not found']);
    }

    public function verifyCode(VerifyCodeRequest $request, SiteSetting $siteSetting)
    {
        $validated = $request->validated();

        $status = $this->forgetPasswordService->verifyTokenOrCode($validated['token'], $validated['email']);

        if ($status === 'expired') {
            return back()->withErrors(['token' => 'Token expired. A new email has been sent.']);
        }

        if (!$status) {
            return back()->withErrors(['token' => 'Invalid token.']);
        }

        return redirect()->route('auth.forget-password.reset-form', [
            'siteSetting' => $siteSetting->slug,
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request, SiteSetting $siteSetting)
    {
        $validated = $request->validated();

        $status = $this->forgetPasswordService->resetPassword($validated['email'], $validated['token'], $validated['password']);

        return match ($status) {
            'expired' => back()->withErrors(['token' => 'Token expired. We sent you a new one.']),
            'invalid' => back()->withErrors(['token' => 'Invalid token.']),
            'success' => to_route('auth.login.index', ['siteSetting' => $siteSetting->slug])->with('success', 'Password successfully reset.'),
        };
    }

    public function resetForm(Request $request, SiteSetting $siteSetting)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
            'siteSetting' => $siteSetting,
        ]);
    }
}
