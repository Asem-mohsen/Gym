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
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required', 'confirmed',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()
            ]
        ];

        if (!$this->is('api/*') && !$this->expectsJson()) $rules['token'] = 'required|string';
        
        return $rules;
    }
}
