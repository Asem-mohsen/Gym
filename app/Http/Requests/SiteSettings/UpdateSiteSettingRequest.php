<?php

namespace App\Http\Requests\SiteSettings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gym_name.en'    => ['required' , 'max:255', 'string'],
            'gym_name.ar'    => ['required' , 'max:255', 'string'],
            'description.en' => ['nullable' , 'max:1000'],
            'description.ar' => ['nullable' , 'max:1000'],
            'size'           => ['sometimes', 'integer'],
            'address.en'     => ['nullable' , 'max:1000'],
            'address.ar'     => ['nullable' , 'max:1000'],
            'contat_email'   => ['nullable' , 'email'],
            'facebook_url'   => ['nullable' , 'url'],
            'x_url'          => ['nullable' , 'url'],
            'instagram_url'  => ['nullable' , 'url'],
        ];
    }
}
