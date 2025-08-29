<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CheckinSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'preferred_checkin_method' => 'required|in:self_scan,gate_scan,both',
            'enable_self_scan' => 'boolean',
            'enable_gate_scan' => 'boolean',
            'require_branch_selection' => 'boolean',
            'allow_multiple_checkins_per_day' => 'boolean',
            'checkin_cooldown_minutes' => 'required|integer|min:1|max:60',
            'enabled_branches' => 'nullable|array',
            'enabled_branches.*' => 'integer|exists:branches,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'preferred_checkin_method.required' => 'Please select a preferred check-in method.',
            'preferred_checkin_method.in' => 'Invalid check-in method selected.',
            'checkin_cooldown_minutes.required' => 'Check-in cooldown is required.',
            'checkin_cooldown_minutes.integer' => 'Check-in cooldown must be a number.',
            'checkin_cooldown_minutes.min' => 'Check-in cooldown must be at least 1 minute.',
            'checkin_cooldown_minutes.max' => 'Check-in cooldown cannot exceed 60 minutes.',
            'enabled_branches.array' => 'Enabled branches must be a list.',
            'enabled_branches.*.integer' => 'Branch ID must be a number.',
            'enabled_branches.*.exists' => 'Selected branch does not exist.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            
            // Ensure at least one check-in method is enabled
            if (isset($data['enable_self_scan']) && isset($data['enable_gate_scan'])) {
                if (!$data['enable_self_scan'] && !$data['enable_gate_scan']) {
                    $validator->errors()->add('enable_self_scan', 'At least one check-in method must be enabled.');
                }
            }

            // Validate preferred method matches enabled methods
            if (isset($data['preferred_checkin_method'])) {
                if ($data['preferred_checkin_method'] === 'self_scan' && isset($data['enable_self_scan']) && !$data['enable_self_scan']) {
                    $validator->errors()->add('preferred_checkin_method', 'Self-scan cannot be preferred if it is disabled.');
                }
                
                if ($data['preferred_checkin_method'] === 'gate_scan' && isset($data['enable_gate_scan']) && !$data['enable_gate_scan']) {
                    $validator->errors()->add('preferred_checkin_method', 'Gate-scan cannot be preferred if it is disabled.');
                }
            }
        });
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'enable_self_scan' => $this->boolean('enable_self_scan'),
            'enable_gate_scan' => $this->boolean('enable_gate_scan'),
            'require_branch_selection' => $this->boolean('require_branch_selection'),
            'allow_multiple_checkins_per_day' => $this->boolean('allow_multiple_checkins_per_day'),
            'enabled_branches' => $this->input('enabled_branches', []),
        ]);
    }
}
