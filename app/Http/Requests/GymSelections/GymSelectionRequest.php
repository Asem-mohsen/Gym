<?php

namespace App\Http\Requests\GymSelections;

use Illuminate\Foundation\Http\FormRequest;

class GymSelectionRequest extends FormRequest
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'sort_by' => 'nullable|in:distance,score,score_and_location,alphabetical',
            'include_distance' => 'nullable|boolean'
        ];
    }

}
