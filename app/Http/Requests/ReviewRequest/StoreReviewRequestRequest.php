<?php

namespace App\Http\Requests\ReviewRequest;

use App\Models\Branch;
use App\Models\BranchScore;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\ReviewRequestService;
use App\Services\SiteSettingService;

class StoreReviewRequestRequest extends FormRequest
{
    protected $reviewRequestService;
    protected $siteSettingId;

    public function __construct(ReviewRequestService $reviewRequestService, SiteSettingService $siteSettingService)
    {
        $this->reviewRequestService = $reviewRequestService;
        $this->siteSettingId = $siteSettingService->getCurrentSiteSettingId();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_reviews_requests');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'branch_score_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $branchScoreExists = BranchScore::whereHas('branch', function($query) {
                        $query->where('site_setting_id', $this->siteSettingId);
                    })->where('id', $value)->exists();
                    
                    $branchExists = Branch::where('site_setting_id', $this->siteSettingId)
                        ->where('id', $value)
                        ->exists();
                    
                    if (!$branchScoreExists && !$branchExists) {
                        $fail('The selected branch is not accessible.');
                    }
                },
            ],
            'scheduled_review_date' => [
                'nullable',
                'date',
                'after:today',
            ],
            'request_notes' => [
                'required',
                'string',
                'max:1000',
            ],
            'supporting_documents' => [
                'required',
                'array',
                'min:1',
            ],
            'supporting_documents.*' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx',
                'max:5120', // 5MB
            ],
            'agree_terms' => [
                'required',
                'accepted',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'branch_score_id.required' => 'Please select a branch.',
            'branch_score_id.exists' => 'The selected branch score does not exist.',
            'scheduled_review_date.after' => 'The scheduled review date must be after today.',
            'request_notes.required' => 'Please provide request notes.',
            'request_notes.max' => 'Request notes cannot exceed 1000 characters.',
            'supporting_documents.required' => 'Please upload at least one supporting document.',
            'supporting_documents.min' => 'Please upload at least one supporting document.',
            'supporting_documents.*.required' => 'Each supporting document is required.',
            'supporting_documents.*.file' => 'Each supporting document must be a valid file.',
            'supporting_documents.*.mimes' => 'Supporting documents must be PDF, Word, Excel, or image files.',
            'supporting_documents.*.max' => 'Each supporting document cannot exceed 5MB.',
            'agree_terms.required' => 'You must agree to the review process.',
            'agree_terms.accepted' => 'You must agree to the review process.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'branch_score_id' => 'branch',
            'scheduled_review_date' => 'preferred review date',
            'request_notes' => 'request notes',
            'supporting_documents' => 'supporting documents',
            'agree_terms' => 'terms agreement',
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
