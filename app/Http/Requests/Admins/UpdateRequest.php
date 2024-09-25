<?php

namespace App\Http\Requests\Admins;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required' , 'max:255'],
            'email'   => ['required' , 'email' , 'max:255' , 'unique:users,email,except,id'],
            'address' => ['required' , 'max:255'],
            'phone'   => ['required' , 'numeric'],
            'roleId'  => ['required' , 'exists:roles,id']
        ];
    }
}
