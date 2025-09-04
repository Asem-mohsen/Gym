@extends('layout.admin.master')

@section('title', 'Score Dashboard')

@section('main-breadcrumb', 'Score Dashboard')
@section('main-breadcrumb-link', route('admin.score-dashboard'))

@section('sub-breadcrumb','Score Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Score Overview Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary border-rounded d-flex align-items-center justify-content-center w-60px h-60px">
                                <i class="fas fa-star text-white fs-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $averageScore ?? 0 }}</h4>
                            <p class="text-muted mb-0">Average Score</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-success border-rounded d-flex align-items-center justify-content-center w-60px h-60px">
                                <i class="fas fa-trophy text-white fs-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $excellentBranches ?? 0 }}</h4>
                            <p class="text-muted mb-0">Excellent Branches</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning border-rounded d-flex align-items-center justify-content-center w-60px h-60px">
                                <i class="fas fa-clock text-white fs-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $pendingReviews ?? 0 }}</h4>
                            <p class="text-muted mb-0">Pending Reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Scores Table -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Branch Scores</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Score</th>
                                    <th>Level</th>
                                    <th>Last Review</th>
                                    <th>Next Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branchScores as $branchScore)
                                <tr>
                                    <td>
                                        <h6 class="mb-0">{{ $branchScore->branch->getTranslation('name', app()->getLocale()) }}</h6>
                                        <small class="text-muted">{{ $branchScore->branch->getTranslation('location', app()->getLocale()) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $branchScore->score_level_color }} fs-6 text-white">{{ $branchScore->score }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $branchScore->score_level_color }} text-white">
                                            {{ ucfirst($branchScore->score_level) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($branchScore->last_review_date)
                                            {{ $branchScore->last_review_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($branchScore->next_review_date)
                                            {{ $branchScore->next_review_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not scheduled</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3"></i>
                                        <p>No branch scores found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('create_reviews_requests')
                            <a href="{{ route('review-requests.create') }}" class="btn btn-primary">
                                <i class="fas fa-clipboard-list me-2"></i>Request Score Review
                            </a>
                        @endcan
                        @can('view_resources')
                            <a href="{{ route('admin.resources') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-file-alt me-2"></i>Resources & Documents
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
