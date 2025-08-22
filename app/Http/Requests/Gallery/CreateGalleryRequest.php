<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class CreateGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'gallery_images.*.image' => 'Gallery images must be valid image files.',
            'gallery_images.*.mimes' => 'Gallery images must be in JPEG, PNG, JPG, GIF, or WEBP format.',
            'gallery_images.*.max' => 'Gallery images must not exceed 2MB.',
        ];
    }
} 