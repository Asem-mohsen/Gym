<?php

namespace App\Http\Requests\Features;

use Illuminate\Foundation\Http\FormRequest;

class AddFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_features');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description' => 'required|array',
            'description.en' => 'required|string',
            'description.ar' => 'required|string',
            'status' => 'boolean',
            'order' => 'integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Feature name is required.',
            'name.en.required' => 'Feature name in English is required.',
            'name.ar.required' => 'Feature name in Arabic is required.',
            'description.required' => 'Feature description is required.',
            'description.en.required' => 'Feature description in English is required.',
            'description.ar.required' => 'Feature description in Arabic is required.',
        ];
    }
} 