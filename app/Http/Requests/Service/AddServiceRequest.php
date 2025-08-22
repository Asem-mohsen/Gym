<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class AddServiceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name.en'       => ['required' , 'max:255' , 'unique:services,name,except,id'],
            'name.ar'       => ['required' , 'max:255', 'string'],
            'description.en'=> ['required' , 'max:1000'],
            'description.ar'=> ['required' , 'max:1000'],
            'duration'      => ['required' , 'integer', 'min:0'],
            'price'         => ['nullable' , 'numeric', 'min:0' , 'required_if:booking_type,paid_booking'],
            'booking_type'  => ['required', 'in:unbookable,free_booking,paid_booking'],
            'is_available'  => ['boolean'],
            'sort_order'    => ['nullable', 'integer', 'min:0'],
            'branches'      => ['required', 'array', 'min:1'],
            'branches.*'    => ['exists:branches,id'],
            'image'         => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'gallery_title' => ['nullable', 'string', 'max:255'],
            'gallery_description' => ['nullable', 'string', 'max:1000'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'price.required_if' => 'Price is required when booking type is paid booking.',
            'branches.*.exists' => 'Selected branch does not exist.',
            'gallery_images.*.image' => 'Gallery images must be valid image files.',
            'gallery_images.*.mimes' => 'Gallery images must be in JPEG, PNG, JPG, GIF, or WEBP format.',
            'gallery_images.*.max' => 'Gallery images must not exceed 2MB.',
            'image.required' => 'Main image is required.',
            'branches.required' => 'At least one branch is required.',
            'branches.min' => 'At least one branch is required.',
        ];
    }
}
