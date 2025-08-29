@extends('layout.admin.master')

@section('title', 'Resources & Documents')

@section('main-breadcrumb', 'Resources')
@section('main-breadcrumb-link', route('admin.resources'))

@section('sub-breadcrumb','Resources & Documents')

@section('content')

<div class="container-fluid">
    <!-- Filters and Search -->
    <div class="row mb-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.resources') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search Documents</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by title or description...">
                        </div>
                        <div class="col-md-3">
                            <label for="type" class="form-label">Document Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="score_document" {{ request('type') == 'score_document' ? 'selected' : '' }}>Score Document</option>
                                <option value="guidelines" {{ request('type') == 'guidelines' ? 'selected' : '' }}>Guidelines</option>
                                <option value="policies" {{ request('type') == 'policies' ? 'selected' : '' }}>Policies</option>
                                <option value="procedures" {{ request('type') == 'procedures' ? 'selected' : '' }}>Procedures</option>
                                <option value="forms" {{ request('type') == 'forms' ? 'selected' : '' }}>Forms</option>
                                <option value="report" {{ request('type') == 'report' ? 'selected' : '' }}>Report</option>
                                <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="type" {{ request('sort') == 'type' ? 'selected' : '' }}>Type</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        @can('view_scores')
                            <a href="{{ route('admin.score-dashboard') }}" class="btn btn-outline-success">
                                <i class="fas fa-chart-line me-2"></i>Score Dashboard
                            </a>
                        @endcan
                        @can('view_reviews_requests')
                            <a href="{{ route('review-requests.index') }}" class="btn btn-outline-secondary"> 
                                <i class="fas fa-clipboard-list me-2"></i>Review Requests
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Grid -->
    <div class="row">
        @forelse($documents as $document)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 document-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="badge text-white bg-{{ $document->document_type === 'score_document' ? 'primary' : 'secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $document->document_type)) }}
                        </span>
                        <small class="text-muted">{{ $document->published_at?->format('M d, Y') ?? 'No date' }}</small>
                    </div>
                    <div class="card-body text-center">
                         <div class="symbol symbol-60px mb-5">
                            <img src="{{ asset('assets/admin/img/files/'.getDocumentExtension($document).'.svg') }}" class="theme-light-show" alt="" />
                         </div>
                        <h5 class="card-title">{{ $document->title }}</h5>
                        @if($document->description)
                            <p class="card-text text-muted">{{$document->description }}</p>
                        @endif
                    </div>
                    @can('download_resources')
                        <div class="card-footer">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.resources.download', $document) }}" 
                                    class="btn btn-sm btn-primary"
                                    title="Download Document">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Documents Found</h4>
                        <p class="text-muted">There are no documents available for your gym at the moment.</p>
                        @if(request('search') || request('type'))
                            <a href="{{ route('admin.resources') }}" class="btn btn-primary">
                                <i class="fas fa-times me-2"></i>Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($documents) && $documents->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{ $documents->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
