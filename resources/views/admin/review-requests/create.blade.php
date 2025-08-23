@extends('layout.admin.master')

@section('title', 'Create Review Request')

@section('main-breadcrumb', 'Review Requests')
@section('main-breadcrumb-link', route('review-requests.index'))

@section('sub-breadcrumb','Create Review Request')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Review Request Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('review-requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="branch_score_id" class="required form-label">Branch</label>
                                @php
                                    $options = [];
                                    foreach($branches as $branch){
                                        $scoreText = $branch->score ? ' (Current Score: ' . $branch->score->score . ')' : ' (No score yet)';
                                        $options[] = [
                                            'value' => $branch->score ? $branch->score->id : $branch->id,
                                            'label' => $branch->name . ' - ' . $branch->location . $scoreText
                                        ];
                                    }
                                @endphp
                                @include('_partials.select',[
                                    'options' => $options,
                                    'name' => 'branch_score_id',
                                    'id' => 'branch_score_id',
                                ])
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="scheduled_review_date" class="required form-label">Preferred Review Date</label>
                                <input type="date" value="{{ old('scheduled_review_date') }}" name="scheduled_review_date" class="form-control form-control-solid @error('scheduled_review_date') is-invalid @enderror" required/>
                                <small class="form-text text-muted">Select your preferred date for the review (optional)</small>
                            </div>
                            <div class="col-md-12 mb-10">
                                <label for="request_notes" class="required form-label">Request Notes</label>
                                <textarea name="request_notes" id="request_notes" rows="4" 
                                      class="form-control form-control-solid @error('request_notes') is-invalid @enderror"
                                      placeholder="Please describe what you would like us to review and any specific areas you want us to focus on...">{{ old('request_notes') }}</textarea>
                            </div>
                            <div class="col-md-12 mb-10">
                                <label for="supporting_documents" class="required form-label">Supporting Documents</label>
                                <input type="file" name="supporting_documents[]" id="supporting_documents" class="form-control form-control-solid @error('supporting_documents') is-invalid @enderror required" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" required>
                                <small class="form-text text-muted">
                                    Upload supporting documents (PDF, Word, Excel, Images). You can select multiple files.
                                </small>
                            </div>

                            <div class="col-md-12 mb-10">
                                <div class="form-check">
                                    <input type="checkbox" name="agree_terms" id="agree_terms" class="form-check-input @error('agree_terms') is-invalid @enderror" required/>
                                    <label for="agree_terms" class="form-check-label">
                                        I agree to the review process and understand that our team will conduct a physical review of the branch
                                    </label>
                                    @error('agree_terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Submit Review Request</button>
                            <a href="{{ route('review-requests.index') }}" class="btn btn-dark">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Review Process Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>How the Review Process Works</h6>
                        <ul class="mb-0">
                            <li>Submit your request with the score document that uploaded from the resources page</li>
                            <li>Our team will review your submission</li>
                            <li>We'll schedule a physical review or a virtual review of your branch</li>
                            <li>You'll receive a detailed report with score updates</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                        <ul class="mb-0">
                            <li>Ensure all documents are clear and legible</li>
                            <li>Provide detailed notes about what you want reviewed</li>
                            <li>Review requests are processed within 5-7 business days</li>
                            <li>You can cancel pending requests at any time</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Branch Information -->
            @if(request('branch_score_id'))
                @php
                    $selectedBranch = collect($branches ?? [])->first(function($branch) {
                        $branchScoreId = $branch->score ? $branch->score->id : $branch->id;
                        return $branchScoreId == request('branch_score_id');
                    });
                @endphp
                @if($selectedBranch)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Selected Branch Information</h5>
                    </div>
                    <div class="card-body">
                        <h6>{{ $selectedBranch->getTranslation('name', app()->getLocale()) }}</h6>
                        <p class="text-muted">{{ $selectedBranch->getTranslation('location', app()->getLocale()) }}</p>
                        
                        @if($selectedBranch->score)
                        <div class="row">
                            <div class="col-6">
                                <strong>Current Score:</strong><br>
                                <span class="badge bg-{{ $selectedBranch->score->score_level_color }} fs-6">
                                    {{ $selectedBranch->score->score }}
                                </span>
                            </div>
                            <div class="col-6">
                                <strong>Level:</strong><br>
                                <span class="badge bg-{{ $selectedBranch->score->score_level_color }}">
                                    {{ ucfirst($selectedBranch->score->score_level) }}
                                </span>
                            </div>
                        </div>
                        
                        @if($selectedBranch->score->last_review_date)
                        <div class="mt-3">
                            <strong>Last Review:</strong><br>
                            <small class="text-muted">{{ $selectedBranch->score->last_review_date->format('M d, Y') }}</small>
                        </div>
                        @endif
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This branch doesn't have a score yet. You can request a review to establish an initial score.
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for scheduled review (tomorrow)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    document.getElementById('scheduled_review_date').min = tomorrowStr;
    
    // Show branch information when branch is selected
    document.getElementById('branch_score_id').addEventListener('change', function() {
        if (this.value) {
            // You can add AJAX here to load branch details dynamically
            location.href = '{{ route("review-requests.create") }}?branch_score_id=' + this.value;
        }
    });
});
</script>

@endsection
