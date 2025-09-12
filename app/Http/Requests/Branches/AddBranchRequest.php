<?php

namespace App\Http\Requests\Branches;

use Illuminate\Foundation\Http\FormRequest;

class AddBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_branches');
    }

    public function rules(): array
    {
        return [
            'manager_id'  => ['required',  'exists:users,id'],
            'name.en'     => ['required' , 'max:255', 'string'],
            'name.ar'     => ['required' , 'max:255', 'string'],
            'location.en' => ['required' , 'max:1000'],
            'location.ar' => ['required' , 'max:1000'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
            'city'        => ['nullable', 'max:255'],
            'region'      => ['nullable', 'max:255'],
            'country'     => ['nullable', 'max:255'],
            'type'        => ['required' , 'in:mix,men,ladies'],
            'size'        => ['required'],
            'facebook_url'=> ['nullable', 'url'],
            'x_url'       => ['nullable', 'url'],
            'instagram_url'=>['nullable', 'url'],
            'map_url'     => ['nullable', 'url'],
            'is_visible'  => ['nullable', 'boolean'],
            'phones'       =>['required', 'array' , 'min:1'],
        ];
    }
}
