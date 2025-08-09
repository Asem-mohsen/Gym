<?php

namespace App\Http\Requests\Locker;

use Illuminate\Foundation\Http\FormRequest;

class LockLockerRequest extends FormRequest
{
    public function authorize()
    {
        // Only allow if user is admin or authorized (customize as needed)
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|string|min:4|max:10',
        ];
    }
} 