<?php

namespace App\Http\Requests\Admins;

use Illuminate\Foundation\Http\FormRequest;

class AddAdminRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()->can('create_admins');
    }

    public function rules(): array
    {
        return [
            'name'    => ['required' , 'max:255'],
            'email'   => ['required' , 'email' , 'max:255' , 'unique:users,email,except,id'],
            'address' => ['required' , 'max:255'],
            'phone'   => ['required' , 'numeric'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
            'gender'  => ['required' , 'in:male,female'],
            'status'  => ['required' , 'in:active,inactive'],
        ];
    }
}
