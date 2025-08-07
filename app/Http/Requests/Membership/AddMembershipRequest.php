<?php

namespace App\Http\Requests\Membership;

use Illuminate\Foundation\Http\FormRequest;

class AddMembershipRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name.en'      => ['required' , 'max:255', 'string'],
            'name.ar'      => ['required' , 'max:255', 'string'],
            'description.en' => ['nullable' , 'max:1000'],
            'description.ar' => ['nullable' , 'max:1000'],
            'status'       => ['required' , 'in:1,0'],
            'order'        => ['required' , 'numeric'],
            'price'        => ['required' , 'numeric'],
            'period'       => ['required'],
            'features'     => ['nullable', 'array'],
            'features.*'   => ['exists:features,id'],
        ];
    }
}
