<?php

namespace App\Http\Requests\Branches;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_branches');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'manager_id'  => ['required' ,  'exists:users,id'],
            'name.en'     => ['required' , 'max:255', 'string'],
            'name.ar'     => ['required' , 'max:255', 'string'],
            'location.en' => ['required' , 'max:1000'],
            'location.ar' => ['required' , 'max:1000'],
            'type'        => ['required' , 'in:mix,men,ladies'],
            'size'        => ['required'],
            'facebook_url'=> ['nullable', 'url'],
            'x_url'       => ['nullable', 'url'],
            'instagram_url'=>['nullable', 'url'],
            'map_url'     => ['nullable', 'url'],
            'phones'       =>['required', 'array' , 'min:1'],
        ];
    }
}
