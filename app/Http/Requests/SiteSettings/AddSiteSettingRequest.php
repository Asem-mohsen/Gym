<?php

namespace App\Http\Requests\SiteSettings;

use Illuminate\Foundation\Http\FormRequest;

class AddSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'owner_id' => 'required|exists:users,id',
            'gym_name' => 'required|string',
            'size' => 'required|integer',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'contact_email' => 'nullable|email',

            'branches' => 'nullable|array',
            'branches.*.name' => 'required|string|max:255',
            'branches.*.address' => 'required|string|max:255',
            'branches.*.phones' => 'nullable|array',
            'branches.*.phones.*.phone_number' => 'required|string|max:15',
        ];
    }
}
