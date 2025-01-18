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
            'gym_name' => 'sometimes|string',
            'size' => 'sometimes|integer',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'contat_email' => 'nullable|email',
        ];
    }
}
