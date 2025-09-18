<?php

namespace App\Http\Requests\Auth\ForgetPassword;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [  
            'email'   => ['required' , 'email' , 'max:255' , 'exists:users,email'],
        ];

        if ($this->is('api/*') || $this->expectsJson()) {
            $rules['code'] = 'required|string|size:5|regex:/^[0-9]{5}$/';
        }else{
            $rules['token'] = 'required|string';
        }

        return $rules;
    }
}
