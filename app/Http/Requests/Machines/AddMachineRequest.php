<?php

namespace App\Http\Requests\Machines;

use Illuminate\Foundation\Http\FormRequest;

class AddMachineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'uuid'],
            'branches'    => ['required', 'array', 'min:1'], 
            'branches.*'  => ['exists:branches,id'],
            'name'        => ['required', 'max:255', 'string'],
            'type'        => ['required', 'max:255'],
            'description' => ['required', 'max:1000'],
            'status'      => ['required', 'in:available,in_use,under_maintenance,needs_maintenance'],
            'next_maintenance_date' => ['nullable', 'date'],
            'last_maintenance_date' => ['nullable', 'date'],
        ];
    }
}
