<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class AddServiceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name.en'       => ['required' , 'max:255' , 'unique:services,name,except,id'],
            'name.ar'       => ['required' , 'max:255', 'string'],
            'description.en'=> ['required' , 'max:1000'],
            'description.ar'=> ['required' , 'max:1000'],
            'duration'      => ['required' , 'max:100'],
            'price'         => ['required' , 'numeric'],
        ];
    }
}
