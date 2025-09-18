<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('sanctum')->check();
    }

    public function rules(): array
    {
        $rules = [
            'name'    => ['required' , 'max:255'],
            'email'   => ['required' , 'email' , 'max:255'],
            'password'=> ['nullable' , 'max:255' , 'confirmed'],
            'address' => ['required' , 'max:255'],
            'gender'  => ['required' , 'max:15'],
            'phone'   => ['required' , 'numeric'],
            'country'   => ['nullable' , 'max:255'],
            'city'      => ['nullable' , 'max:255'],
            'image'   => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif' , 'max:2048'],
        ];

        // Add trainer information validation if user has trainer role
        if ($this->hasTrainerRole()) {
            $rules = array_merge($rules, [
                'weight' => 'nullable|numeric|min:0|max:999.99',
                'height' => 'nullable|numeric|min:0|max:999.99',
                'date_of_birth' => 'nullable|date|before:today',
                'brief_description' => 'nullable|string|max:1000',
                'facebook_url' => 'nullable|url|max:255',
                'twitter_url' => 'nullable|url|max:255',
                'instagram_url' => 'nullable|url|max:255',
                'youtube_url' => 'nullable|url|max:255',
            ]);
        }

        return $rules;
    }

    /**
     * Check if the user has trainer role
     */
    private function hasTrainerRole(): bool
    {
        /**
         * @var User $user
         */
        $user = Auth::guard('sanctum')->user();
        if (!$user) return false;

        return $user->hasRole('trainer');
    }
}