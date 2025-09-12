<?php

namespace App\Http\Requests\GymPermissions;

use Illuminate\Foundation\Http\FormRequest;

class AssignUserPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assign_roles');
    }

    public function rules(): array
    {
        return [
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'permission_ids.array' => 'Permissions must be an array.',
            'permission_ids.*.string' => 'Permission names must be strings.',
        ];
    }
}
