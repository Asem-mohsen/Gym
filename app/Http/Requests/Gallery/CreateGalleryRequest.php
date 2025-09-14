<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class CreateGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_gallery');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'required|in:0,1',
            'sort_order' => 'integer|min:0',
            'pages' => 'required|array|min:1',
            'pages.*' => 'required|string|in:home,about,services,classes,gallery,contact,branch',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'pages.required' => 'Please select at least one page to display the gallery.',
            'pages.min' => 'Please select at least one page to display the gallery.',
            'pages.*.in' => 'Invalid page selection.',
            'gallery_images.*.image' => 'Gallery images must be valid image files.',
            'gallery_images.*.mimes' => 'Gallery images must be in JPEG, PNG, JPG, GIF, or WEBP format.',
            'gallery_images.*.max' => 'Gallery images must not exceed 2MB.',
        ];
    }
} 