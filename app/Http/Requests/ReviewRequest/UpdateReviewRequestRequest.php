<?php

namespace App\Http\Requests\ReviewRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_reviews_requests');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'request_notes' => [
                'required',
                'string',
                'max:1000',
            ],
            'scheduled_review_date' => [
                'nullable',
                'date',
                'after:today',
            ],
            'supporting_documents.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx',
                'max:5120', // 5MB
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'request_notes.required' => 'Please provide request notes.',
            'request_notes.max' => 'Request notes cannot exceed 1000 characters.',
            'scheduled_review_date.after' => 'The scheduled review date must be after today.',
            'supporting_documents.*.file' => 'Each supporting document must be a valid file.',
            'supporting_documents.*.mimes' => 'Supporting documents must be PDF, Word, Excel, or image files.',
            'supporting_documents.*.max' => 'Each supporting document cannot exceed 5MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'request_notes' => 'request notes',
            'scheduled_review_date' => 'preferred review date',
            'supporting_documents' => 'supporting documents',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->scheduled_review_date === '') {
            $this->merge(['scheduled_review_date' => null]);
        }
    }
}
