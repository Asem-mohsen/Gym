<?php

namespace App\Http\Requests\Users;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role as SpatieRole;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()->can('edit_users') || $this->user()->can('edit_staff') || $this->user()->can('edit_trainers') || $this->user()->can('edit_admins');
    }

    public function rules(): array
    {
        $rules = [
            'name'    => ['required' , 'max:255'],
            'email'   => ['required' , 'email' , 'max:255'],
            'password'=> ['nullable' , 'max:255' , 'confirmed'],
            'address' => ['required' , 'max:255'],
            'gender'  => ['required' , 'max:15'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
            'branch_ids' => ['nullable', 'array'],
            'branch_ids.*' => ['exists:branches,id'],
            'phone'   => ['required' , 'numeric'],
            'country'   => ['required' , 'max:255'],
            'city'      => ['required' , 'max:255'],
            'image'   => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif' , 'max:2048'],
        ];

        // Add trainer information validation if user has trainer role
        if ($this->hasTrainerRole()) {
            $rules = array_merge($rules, [
                'branches' => 'required|array',
                'branches.*' => 'exists:branches,id',
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
        $roleIds = $this->input('role_ids', []);
        if (empty($roleIds)) return false;

        $trainerRole = SpatieRole::where('name', 'trainer')->first();
        if (!$trainerRole) return false;

        return in_array($trainerRole->id, $roleIds);
    }
}
