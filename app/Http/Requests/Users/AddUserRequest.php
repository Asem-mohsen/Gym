<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
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
            'gender'  => ['required' , 'max:15'],
            'phone'   => ['required' , 'numeric'],
            'role_id' => ['required' , 'exists:roles,id'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
