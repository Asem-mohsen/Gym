<?php

namespace App\Http\Requests\Membership;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMembershipRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required' , 'max:255'],
            'description'   => ['nullable' , 'max:1000'],
            'description_ar'=> ['nullable' , 'max:1000'],
            'price'         => ['required' , 'numeric'],
        ];
    }
}
