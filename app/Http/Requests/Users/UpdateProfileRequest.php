<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();
        
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => [
                'required', 
                'email', 
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password'=> ['nullable', 'string', 'min:8', 'confirmed'],
            'address' => ['nullable', 'string', 'max:255'],
            'gender'  => ['nullable', 'string', 'in:male,female,other'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'image'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'photos'  => ['nullable', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'photo_titles' => ['nullable', 'array'],
            'photo_titles.*' => ['nullable', 'string', 'max:255'],
            'new_photo_titles' => ['nullable', 'array'],
            'new_photo_titles.*' => ['nullable', 'string', 'max:255'],
            'delete_photos' => ['nullable', 'array'],
            'delete_photos.*' => ['integer', 'exists:user_photos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.max' => 'Full name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'gender.in' => 'Please select a valid gender.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
            'photos.max' => 'You can upload maximum 10 photos.',
            'photos.*.image' => 'Each file must be an image.',
            'photos.*.mimes' => 'Each image must be a file of type: jpeg, png, jpg, gif, webp.',
            'photos.*.max' => 'Each image may not be greater than 5MB.',
            'photo_titles.*.max' => 'Photo title cannot exceed 255 characters.',
            'new_photo_titles.*.max' => 'Photo title cannot exceed 255 characters.',
            'delete_photos.*.exists' => 'One or more photos to delete do not exist.',
        ];
    }
}
