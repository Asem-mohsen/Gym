<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class AddSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_subscriptions');
    }

    public function rules(): array
    {
        return [
            'user_id'       => ['required', 'exists:users,id'],
            'membership_id' => ['required', 'exists:memberships,id'],
            'branch_id'     => ['required', 'exists:branches,id'],
            'status'        => ['required', 'in:active,pending,cancelled,expired'],
            'offer_id'      => ['sometimes', 'nullable', 'exists:offers,id'],
            'amount'        => ['nullable', 'required_if:status,active', 'numeric'],
            'start_date'    => ['nullable', 'date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
    
}
