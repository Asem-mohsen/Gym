<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name'          => ['required' , 'max:255' ],
            'description'   => ['nullable' , 'max:1000'],
            'description_ar'=> ['nullable' , 'max:1000'],
            'duration'      => ['nullable' , 'max:100'],
            'price'         => ['required' , 'numeric'],
        ];
    }
}
