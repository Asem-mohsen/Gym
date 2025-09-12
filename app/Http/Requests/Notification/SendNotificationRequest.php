<?php

namespace App\Http\Requests\Notification;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create_notifications');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'target_roles' => 'required|array|min:1',
            'target_roles.*' => 'required|string|in:regular_user,trainer,sales,management',
            'priority' => 'nullable|string|in:low,normal,high,urgent',
            'details' => 'nullable|array',
            'details.*' => 'nullable|string|max:500',
            'action_url' => 'nullable|url|max:500',
            'action_text' => 'nullable|string|max:100',
            'scheduled_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:now',
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
            'subject.required' => 'A notification subject is required.',
            'subject.max' => 'The subject cannot exceed 255 characters.',
            'message.required' => 'A notification message is required.',
            'message.max' => 'The message cannot exceed 1000 characters.',
            'target_roles.required' => 'Please select at least one target role.',
            'target_roles.array' => 'Target roles must be selected as a list.',
            'target_roles.min' => 'Please select at least one target role.',
            'target_roles.*.in' => 'Invalid target role selected.',
            'priority.in' => 'Invalid priority level selected.',
            'details.*.max' => 'Each detail line cannot exceed 500 characters.',
            'action_url.url' => 'Please provide a valid URL for the action button.',
            'action_url.max' => 'The action URL cannot exceed 500 characters.',
            'action_text.max' => 'The action button text cannot exceed 100 characters.',
            'scheduled_at.date' => 'Please provide a valid scheduled date.',
            'scheduled_at.after' => 'The scheduled date must be in the future.',
            'expires_at.date' => 'Please provide a valid expiration date.',
            'expires_at.after' => 'The expiration date must be in the future.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'subject' => 'notification subject',
            'message' => 'notification message',
            'target_roles' => 'target roles',
            'priority' => 'priority level',
            'details' => 'additional details',
            'action_url' => 'action URL',
            'action_text' => 'action button text',
            'scheduled_at' => 'scheduled date',
            'expires_at' => 'expiration date',
        ];
    }
}
