<?php

namespace App\Http\Requests\GymBranding;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGymBrandingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_site_settings');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Form type
            'form_type' => 'nullable|string|in:colors_typography,page_sections,media_settings,page_texts,repeater_fields',
            
            // Colors - only validate if not empty
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            
            // Typography & Styling
            'font_family' => 'nullable|string|max:255',
            'border_radius' => 'nullable|string|max:50',
            'box_shadow' => 'nullable|string|max:255',
            
            // Page Sections
            'home_page_sections' => 'nullable|array',
            'home_page_sections.*' => 'boolean',
            'section_styles' => 'nullable|array',
            
            // Media Settings
            'media_settings' => 'nullable|array',
            'media_settings.*' => 'nullable',
            'media_settings.*.*' => 'nullable|file|image|max:5120', // 5MB max
            
            // Page Texts
            'page_type' => 'nullable|string|in:login,register,auth_common,home,about,services,contact,team,gallery,classes',
            'texts' => 'nullable|array',
            'texts.*' => 'nullable|string|max:1000',
            
            // Repeater Fields
            'section' => 'nullable|string',
            'repeater_data' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'primary_color.regex' => 'Primary color must be a valid hex color (e.g., #ff0000 or #f00)',
            'secondary_color.regex' => 'Secondary color must be a valid hex color (e.g., #ff0000 or #f00)',
            'accent_color.regex' => 'Accent color must be a valid hex color (e.g., #ff0000 or #f00)',
            'text_color.regex' => 'Text color must be a valid hex color (e.g., #ff0000 or #f00)',
            'media_settings.*.image' => 'Media files must be valid images',
            'media_settings.*.max' => 'Media files must be smaller than 5MB',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'primary_color' => 'primary color',
            'secondary_color' => 'secondary color',
            'accent_color' => 'accent color',
            'text_color' => 'text color',
            'font_family' => 'font family',
            'border_radius' => 'border radius',
            'box_shadow' => 'box shadow',
            'home_page_sections' => 'home page sections',
            'section_styles' => 'section styles',
            'media_settings' => 'media settings',
            'page_type' => 'page type',
            'texts' => 'texts',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $mediaSettings = $this->input('media_settings', []);
            $multipleFileTypes = \App\Models\GymSetting::getMultipleFileMediaTypes();
            
            foreach ($mediaSettings as $mediaType => $files) {
                if (empty($files)) continue;
                
                $maxFiles = $multipleFileTypes[$mediaType] ?? 1;
                
                if ($maxFiles > 1) {
                    // Multiple files expected
                    if (!is_array($files)) {
                        $validator->errors()->add("media_settings.{$mediaType}", "The {$mediaType} field must be an array for multiple files.");
                    } else {
                        foreach ($files as $index => $file) {
                            if ($file && !$file->isValid()) {
                                $validator->errors()->add("media_settings.{$mediaType}.{$index}", "Invalid file at position {$index}.");
                            }
                        }
                    }
                } else {
                    // Single file expected
                    if (is_array($files)) {
                        $validator->errors()->add("media_settings.{$mediaType}", "The {$mediaType} field must be a single file.");
                    } elseif ($files && !$files->isValid()) {
                        $validator->errors()->add("media_settings.{$mediaType}", "Invalid file.");
                    }
                }
            }
            
            // Validate repeater_data JSON
            $repeaterData = $this->input('repeater_data');
            if ($repeaterData && !empty($repeaterData)) {
                $decoded = json_decode($repeaterData, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $validator->errors()->add('repeater_data', 'The repeater data must be valid JSON.');
                } elseif (!is_array($decoded)) {
                    $validator->errors()->add('repeater_data', 'The repeater data must be a valid array.');
                }
            }
        });
    }
}
