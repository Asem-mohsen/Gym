<?php

namespace App\Http\Requests\Deactivation;

use Illuminate\Foundation\Http\FormRequest;

class DeactivationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('deactivate_gyms_and_branches');
    }

    public function rules(): array
    {
        return [];
    }
}