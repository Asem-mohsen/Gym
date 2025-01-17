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
            'name'          => ['required' , 'max:255' , 'unique:memberships,name,except,id'],
            'description'   => ['nullable' , 'max:1000'],
            'description_ar'=> ['nullable' , 'max:1000'],
            'price'         => ['required' , 'numeric'],
        ];
    }
}
