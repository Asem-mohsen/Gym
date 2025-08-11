<?php

namespace App\Http\Requests\Users;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'    => ['required' , 'max:255'],
            'email'   => ['required' , 'email' , 'max:255'],
            'password'=> ['nullable' , 'max:255' , 'confirmed'],
            'address' => ['required' , 'max:255'],
            'gender'  => ['required' , 'max:15'],
            'role_id' => ['required' , 'exists:roles,id'],
            'phone'   => ['required' , 'numeric'],
            'image'   => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif' , 'max:2048'],
        ];

        // Add trainer information validation if role is trainer
        if ($this->input('role_id') && $this->isTrainerRole()) {
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
     * Check if the selected role is trainer
     */
    private function isTrainerRole(): bool
    {
        $roleId = $this->input('role_id');
        if (!$roleId) return false;

        $role = Role::find($roleId);
        return $role && strtolower($role->name) === 'trainer';
    }
}
