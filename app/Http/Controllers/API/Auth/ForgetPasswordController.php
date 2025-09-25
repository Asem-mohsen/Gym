<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPassword\{ResetPasswordRequest, SendCodeRequest, VerifyCodeRequest};
use App\Models\SiteSetting;
use App\Services\Auth\ForgetPasswordService;

class ForgetPasswordController extends Controller
{
    public function __construct(protected ForgetPasswordService $forgetPasswordService) 
    {
        $this->forgetPasswordService = $forgetPasswordService;
    }

    public function sendCode(SendCodeRequest $request, SiteSetting $gym)
    {
        $validated = $request->validated();

        $success = $this->forgetPasswordService->sendResetToken($validated['email'], 'code', $gym);

        return successResponse($success, 'If the provided email is registered in our system, a password reset code has been sent to it.');
    }

    public function verifyCode(VerifyCodeRequest $request, SiteSetting $gym)
    {
        $validated = $request->validated();

        $status = $this->forgetPasswordService->verifyTokenOrCode($validated['code'], $validated['email'], 'code', $gym);

        if ($status === 'expired') {
            return failureResponse('Code expired. A new email has been sent.');
        }

        if (!$status) {
            return failureResponse('Invalid code.');
        }

        return successResponse($status, 'Code verified successfully.');
    }

    public function resetPassword(ResetPasswordRequest $request, SiteSetting $gym)
    {
        $validated = $request->validated();

        $status = $this->forgetPasswordService->resetPassword(email: $validated['email'], token: null,newPassword: $validated['password'], gym: $gym);

        return match ($status) {
            'expired' => failureResponse('Code expired. We sent you a new one.'),
            'invalid' => failureResponse('Invalid code.'),
            'success' => successResponse($status, 'Password successfully reset.'),
        };
    }
}
