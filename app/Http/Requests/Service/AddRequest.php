<?php

namespace App\Http\Requests\Service;

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
            'name'          => ['required' , 'max:255' , 'unique:services,name,except,id'],
            'description'   => ['nullable' , 'max:1000'],
            'description_ar'=> ['nullable' , 'max:1000'],
            'duration'      => ['nullable' , 'max:100'],
            'price'         => ['required' , 'numeric'],
        ];
    }
}
