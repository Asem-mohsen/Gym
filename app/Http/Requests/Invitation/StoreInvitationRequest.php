<?php

namespace App\Http\Requests\Invitation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInvitationRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invitee_email' => ['required', 'email', 'max:255'],
            'invitee_phone' => ['required', 'string', 'max:20'],
            'invitee_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'invitee_email.required' => 'Please provide the email address of the person you want to invite.',
            'invitee_email.email' => 'Please provide a valid email address.',
            'invitee_phone.required' => 'Please provide the phone number of the person you want to invite.',
            'invitee_name.max' => 'The invitee name cannot exceed 255 characters.',
        ];
    }
}
