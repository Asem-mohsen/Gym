<?php

namespace App\Http\Requests\Membership;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\MembershipPeriod;

class UpdateMembershipRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()->can('edit_memberships');
    }

    public function rules(): array
    {
        return [
            'name.en'      => ['required' , 'max:255', 'string'],
            'name.ar'      => ['required' , 'max:255', 'string'],
            'subtitle.en'  => ['nullable' , 'max:1000'],
            'subtitle.ar'  => ['nullable' , 'max:1000'],
            'general_description.en' => ['nullable' , 'max:1000'],
            'general_description.ar' => ['nullable' , 'max:1000'],
            'status'       => ['required' , 'in:1,0'],
            'price'        => ['required' , 'numeric'],
            'order'        => ['required' , 'numeric'],
            'invitation_limit' => ['required', 'integer', 'min:0'],
            'period'       => ['required', 'string', function($attribute, $value, $fail) {
                if (!MembershipPeriod::isValid($value)) {
                    $fail('The selected period is invalid.');
                }
            }],
            'features'     => ['nullable', 'array'],
            'features.*'   => ['exists:features,id'],
        ];
    }
}
