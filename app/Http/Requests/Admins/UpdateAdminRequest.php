<?php

namespace App\Http\Requests\Admins;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required' , 'max:255'],
            'email'   => ['required' , 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('admin')->id)],
            'address' => ['required' , 'max:255'],
            'password'=> ['nullable' , 'max:255'],
            'phone'   => ['required' , 'numeric'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
            'gender'  => ['nullable' , 'in:male,female'],
            'status'  => ['required' , 'in:1,0'],
        ];
    }
}
