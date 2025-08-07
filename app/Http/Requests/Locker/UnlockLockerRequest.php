<?php

namespace App\Http\Requests\Locker;

use Illuminate\Foundation\Http\FormRequest;

class UnlockLockerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|string|min:4|max:10',
        ];
    }
} 