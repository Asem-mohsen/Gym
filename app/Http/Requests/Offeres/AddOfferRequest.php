<?php

namespace App\Http\Requests\Offeres;

use Illuminate\Foundation\Http\FormRequest;

class AddOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_offers');
    }

    public function rules(): array
    {
        return [
            'title.en'       => ['required' , 'max:255', 'string'],
            'title.ar'       => ['required' , 'max:255', 'string'],
            'description.en' => ['nullable' , 'max:1000'],
            'description.ar' => ['nullable' , 'max:1000'],
            'start_date'     => 'required|date',
            'discount_type'  => 'required|in:fixed,percentage',
            'discount_value' => 'required|string',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'assign_to'      => 'required|array|min:1',
            'assign_to.*'    => 'in:App\Models\Membership,App\Models\Service',
            'memberships'    => 'nullable|array',
            'services'       => 'nullable|array',
        ];
    }
}
