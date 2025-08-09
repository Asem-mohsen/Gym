<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPassword\{ResetPasswordRequest, SendCodeRequest, VerifyCodeRequest};
use App\Services\Auth\ForgetPasswordService;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function __construct(protected ForgetPasswordService $forgetPasswordService) {}

    public function sendCode(SendCodeRequest $request)
    {
        $validated = $request->validated();

        $success = $this->forgetPasswordService->sendResetToken($validated['email']);

        return successResponse($success, 'If the provided email is registered in our system, a password reset link has been sent to it.');
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $validated = $request->validated();

        $status = $this->forgetPasswordService->verifyToken($validated['token'], $validated['email']);

        if ($status === 'expired') {
            return failureResponse('Token expired. A new email has been sent.');
        }

        if (!$status) {
            return failureResponse('Invalid token.');
        }

        return successResponse($status, 'Token verified successfully.');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $status = $this->forgetPasswordService->resetPassword($validated['email'], $validated['token'], $validated['password']);

        return match ($status) {
            'expired' => failureResponse('Token expired. We sent you a new one.'),
            'invalid' => failureResponse('Invalid token.'),
            'success' => successResponse($status, 'Password successfully reset.'),
        };
    }

    public function resetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }
}
