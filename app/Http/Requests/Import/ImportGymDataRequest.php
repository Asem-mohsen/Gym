<?php

namespace App\Http\Requests\Import;

use Illuminate\Foundation\Http\FormRequest;

class ImportGymDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('import_gym_data');
    }

    public function rules(): array
    {
        return [
            'import_file' => 'required|file|max:10240',
        ];
    }
}