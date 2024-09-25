<?php

namespace App\Http\Requests\Admins;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
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
            'password'=> ['required' , 'max:255'],
            'address' => ['required' , 'max:255'],
            'phone'   => ['required' , 'numeric'],
            'isAdmin' => ['required' , 'boolean'],
            'roleId'  => ['required' , 'exists:roles,id']
        ];
    }
}
