<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name.en'       => ['required' , 'max:255', 'string'],
            'name.ar'       => ['required' , 'max:255', 'string'],
            'description.en'=> ['nullable' , 'max:1000'],
            'description.ar'=> ['nullable' , 'max:1000'],
            'duration'      => ['required' , 'max:100'],
            'price'         => ['required' , 'numeric'],
            'image'         => ['nullable'],
        ];
    }
}
