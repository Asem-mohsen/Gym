@extends('layout.admin.master')

@section('title', 'Edit Review Request')

@section('main-breadcrumb', 'Review Requests')
@section('main-breadcrumb-link', route('review-requests.index'))

@section('sub-breadcrumb','Edit Review Request')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Review Request Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('review-requests.update', $reviewRequest->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="branch_score_id" class="required form-label">Branch</label>
                                <input type="text" class="form-control form-control-solid" value="{{ $reviewRequest->branchScore->branch->name }} - {{ $reviewRequest->branchScore->branch->location }}" readonly>
                                <input type="hidden" name="branch_score_id" value="{{ $reviewRequest->branch_score_id }}">
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="scheduled_review_date" class="required form-label">Preferred Review Date</label>
                                <input type="date" name="scheduled_review_date" id="scheduled_review_date" 
                                       class="form-control form-control-solid @error('scheduled_review_date') is-invalid @enderror" value="{{ old('scheduled_review_date', $reviewRequest->scheduled_review_date?->format('Y-m-d')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                <small class="form-text text-muted">Select your preferred date for the review (optional)</small>
                            </div>

                            <div class="col-md-12">
                                <label for="request_notes" class="form-label">Request Notes</label>
                                <textarea name="request_notes" id="request_notes" rows="4" class="form-control form-control-solid @error('request_notes') is-invalid @enderror" placeholder="Please describe what you would like us to review and any specific areas you want us to focus on...">{{ old('request_notes', $reviewRequest->request_notes) }}</textarea>
                            </div>
                            
                            <div class="col-md-12 mt-5">
                                <label for="supporting_documents" class="form-label">Additional Supporting Documents</label>
                                <input type="file" name="supporting_documents[]" id="supporting_documents"  class="form-control @error('supporting_documents') is-invalid @enderror" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx">
                                <small class="form-text text-muted">
                                    Upload additional supporting documents (PDF, Word, Excel, Images). You can select multiple files.
                                </small>
                            </div>
                        </div>

                        <div class="card-footer">
                            @can('edit_reviews_requests')
                                <button type="submit" class="btn btn-success">Update Review Request</button>
                            @endcan
                            <a href="{{ route('review-requests.index') }}" class="btn btn-dark">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Current Request Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Current Request Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <strong>Request Date:</strong>
                            <span class="text-muted">{{ $reviewRequest->requested_at?->format('M d, Y H:i') ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $reviewRequest->status_color }}">
                                {{ ucfirst($reviewRequest->status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($reviewRequest->scheduled_review_date)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <strong>Your Preferred Review Date:</strong>
                                <span class="text-muted">{{ $reviewRequest->scheduled_review_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Branch Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Branch Information</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $reviewRequest->branchScore->branch->getTranslation('name', app()->getLocale()) }}</h6>
                    <p class="text-muted">{{ $reviewRequest->branchScore->branch->getTranslation('location', app()->getLocale()) }}</p>
                    
                    <div class="row">
                        <div class="col-6">
                            <strong>Current Score:</strong>
                            <span class="badge bg-{{ $reviewRequest->branchScore->score_level_color }} fs-6">
                                {{ $reviewRequest->branchScore->score }}
                            </span>
                        </div>
                        <div class="col-6">
                            <strong>Level:</strong>
                            <span class="badge bg-{{ $reviewRequest->branchScore->score_level_color }}">
                                {{ ucfirst($reviewRequest->branchScore->score_level) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Existing Documents -->
            @if($reviewRequest->getMedia('supporting_documents')->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Current Supporting Documents</h5>
                    </div>
                    <div class="card-body card-body d-flex justify-content-between flex-wrap">
                        @foreach($reviewRequest->getMedia('supporting_documents') as $media)
                            <div class="d-flex align-items-center mb-2 w-100">
                                <div class="symbol symbol-60px mb-5">
                                    <img src="{{ asset('assets/admin/img/files/'.getDocumentExtension($media->file_name).'.svg') }}" class="theme-light-show" alt="" />
                                </div>
                                <div class="flex-grow-1">
                                    <small class="d-block">{{ $media->name }}</small>
                                </div>
                                <a href="{{ $media->getUrl() }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Information Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Edit Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>What You Can Edit</h6>
                        <ul class="mb-0">
                            <li>Preferred review date</li>
                            <li>Request notes and description</li>
                            <li>Add more supporting documents</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                        <ul class="mb-0">
                            <li>You cannot change the selected branch</li>
                            <li>Existing documents will be preserved</li>
                            <li>New documents will be added to existing ones</li>
                            <li>Changes will be reviewed by our team</li>
                        </ul>
                    </div>
                </div>
            </div>
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
});
</script>

@endsection
