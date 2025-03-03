<?php

namespace App\Http\Requests\Branches;

use Illuminate\Foundation\Http\FormRequest;

class AddBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manager_id'  => ['required',  'exists:users,id'],
            'name.en'     => ['required' , 'max:255', 'string'],
            'name.ar'     => ['required' , 'max:255', 'string'],
            'location.en' => ['required' , 'max:1000'],
            'location.ar' => ['required' , 'max:1000'],
            'type'        => ['required' , 'in:mix,men,ladies'],
            'size'        => ['required'],
            'facebook_url'=> ['nullable'],
            'x_url'       => ['nullable'],
            'instagram_url'=>['nullable'],
            'phones'       =>['required', 'array' , 'min:1'],
        ];
    }
}
