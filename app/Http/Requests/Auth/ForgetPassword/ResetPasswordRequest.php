<?php

namespace App\Http\Requests\Auth\ForgetPassword;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => [
                'required', 'confirmed',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()
            ]
        ];
    }
}
