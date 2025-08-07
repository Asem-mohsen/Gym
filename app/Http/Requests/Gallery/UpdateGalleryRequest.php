<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'media_files' => ['nullable', 'array'],
            'media_files.*.file' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'], // 10MB max
            'media_files.*.title' => ['nullable', 'string', 'max:255'],
            'media_files.*.alt_text' => ['nullable', 'string', 'max:255'],
            'media_files.*.caption' => ['nullable', 'string', 'max:500'],
            'media_properties' => ['nullable', 'array'],
            'media_properties.*.media_id' => ['required', 'integer', 'exists:media,id'],
            'media_properties.*.title' => ['nullable', 'string', 'max:255'],
            'media_properties.*.alt_text' => ['nullable', 'string', 'max:255'],
            'media_properties.*.caption' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Gallery title is required.',
            'title.max' => 'Gallery title cannot exceed 255 characters.',
            'description.max' => 'Gallery description cannot exceed 1000 characters.',
            'media_files.*.file.required' => 'Media file is required.',
            'media_files.*.file.image' => 'File must be an image.',
            'media_files.*.file.mimes' => 'File must be a valid image format (jpeg, png, jpg, gif, webp).',
            'media_files.*.file.max' => 'File size cannot exceed 10MB.',
            'media_files.*.title.max' => 'Image title cannot exceed 255 characters.',
            'media_files.*.alt_text.max' => 'Alt text cannot exceed 255 characters.',
            'media_files.*.caption.max' => 'Caption cannot exceed 500 characters.',
            'media_properties.*.media_id.required' => 'Media ID is required.',
            'media_properties.*.media_id.exists' => 'Media ID does not exist.',
            'media_properties.*.title.max' => 'Image title cannot exceed 255 characters.',
            'media_properties.*.alt_text.max' => 'Alt text cannot exceed 255 characters.',
            'media_properties.*.caption.max' => 'Caption cannot exceed 500 characters.',
        ];
    }
} 