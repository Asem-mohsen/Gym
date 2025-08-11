<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePaymentIntentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'membership_id' => 'required|exists:memberships,id',
            'offer_id' => 'nullable|exists:offers,id',
            'site_setting_id' => 'required|exists:site_settings,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'membership_id.required' => 'Membership ID is required.',
            'membership_id.exists' => 'Selected membership does not exist.',
            'offer_id.exists' => 'Selected offer does not exist.',
            'site_setting_id.required' => 'Site setting ID is required.',
            'site_setting_id.exists' => 'Selected site setting does not exist.',
        ];
    }
}
