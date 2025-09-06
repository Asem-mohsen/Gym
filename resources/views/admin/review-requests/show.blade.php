@extends('layout.admin.master')

@section('title', 'Review Request Details')

@section('main-breadcrumb', 'Review Requests')
@section('main-breadcrumb-link', route('review-requests.index'))

@section('sub-breadcrumb','Review Request Details')

@section('toolbar-actions')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="d-flex justify-content-between" data-kt-table-toolbar="base">
                @if($reviewRequest->status === 'pending')
                    @can('edit_reviews_requests')
                    <a href="{{ route('review-requests.edit', $reviewRequest->id) }}" class="btn btn-primary">
                        Edit Request
                    </a>
                    @endcan
                @endif
                @can('view_reviews_requests')
                    <a href="{{ route('review-requests.index') }}" class="btn btn-secondary mx-2">
                        Back to List
                    </a>
                @endcan
            </div>
        </div>
    </div>

@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Request Details Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Request Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Request Status</label>
                                <div>
                                    <span class="badge bg-{{ $reviewRequest->status_color }} fs-6 text-white">
                                        {{ ucfirst($reviewRequest->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Request Date</label>
                                <div class="text-muted">
                                    {{ $reviewRequest->requested_at?->format('M d, Y H:i') ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Preferred Review Date</label>
                                <div class="text-muted">
                                    @if($reviewRequest->scheduled_review_date)
                                        {{ $reviewRequest->scheduled_review_date->format('M d, Y') }}
                                    @else
                                        Not scheduled
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Review Date</label>
                                <div class="text-muted">
                                    @if($reviewRequest->reviewed_at)
                                        {{ $reviewRequest->reviewed_at->format('M d, Y H:i') }}
                                    @else
                                        Not reviewed yet
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($reviewRequest->request_notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Request Notes</label>
                            <div class="p-3 bg-light rounded">
                                {{ $reviewRequest->request_notes }}
                            </div>
                        </div>
                    @endif

                    @if($reviewRequest->review_notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Review Notes</label>
                            <div class="p-3 bg-light rounded">
                                {{ $reviewRequest->review_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Supporting Documents -->
            @if($reviewRequest->getMedia('supporting_documents')->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Supporting Documents</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($reviewRequest->getMedia('supporting_documents') as $media)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="symbol symbol-60px mb-5">
                                            <img src="{{ asset('assets/admin/img/files/'.getDocumentExtension($media->file_name).'.svg') }}" class="theme-light-show" alt="" />
                                        </div>
                                        <h6 class="card-title">{{ $media->name }}</h6>
                                        <a href="{{ $media->getUrl() }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Branch Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Branch Information</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $reviewRequest->branchScore->branch->getTranslation('name', app()->getLocale()) }}</h6>
                    <p class="text-muted">{{ $reviewRequest->branchScore->branch->getTranslation('location', app()->getLocale()) }}</p>
                    
                    <div class="row">
                        <div class="col-6">
                            <strong>Current Score:</strong>
                            <span class="badge bg-{{ $reviewRequest->branchScore->score_level_color }} fs-6 text-white">
                                {{ $reviewRequest->branchScore->score }}
                            </span>
                        </div>
                        <div class="col-6">
                            <strong>Level:</strong>
                            <span class="badge bg-{{ $reviewRequest->branchScore->score_level_color }} text-white">
                                {{ ucfirst($reviewRequest->branchScore->score_level) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($reviewRequest->branchScore->last_review_date)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <strong>Last Review:</strong><br>
                                <small class="text-muted">{{ $reviewRequest->branchScore->last_review_date->format('M d, Y') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Review Information -->
            @if($reviewRequest->is_reviewed)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Review Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <strong>Reviewed By:</strong><br>
                            <small class="text-muted">{{ $reviewRequest->reviewedBy?->name ?? 'Unknown' }}</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <strong>Review Decision:</strong><br>
                            <span class="badge bg-{{ $reviewRequest->is_approved ? 'success' : 'danger' }} text-white">
                                {{ $reviewRequest->is_approved ? 'Approved' : 'Rejected' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Timeline -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Request Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Request Submitted</h6>
                                <p class="timeline-text">{{ $reviewRequest->requested_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        @if($reviewRequest->scheduled_review_date)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Review Scheduled</h6>
                                <p class="timeline-text">{{ $reviewRequest->scheduled_review_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($reviewRequest->is_reviewed)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $reviewRequest->is_approved ? 'success' : 'danger' }}"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Review Completed</h6>
                                <p class="timeline-text">{{ $reviewRequest->reviewed_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($reviewRequest->status === 'pending')
                @can('edit_reviews_requests')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('review-requests.edit', $reviewRequest->id) }}" class="btn btn-primary">
                                    Edit Request
                                </a>
                            </div>
                        </div>
                    </div>
                @endcan
            @endif
        </div>
    </div>
</div>
@endsection
