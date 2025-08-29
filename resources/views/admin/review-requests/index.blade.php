@extends('layout.admin.master')

@section('title', 'Review Requests')

@section('main-breadcrumb', 'Review Requests')
@section('main-breadcrumb-link', route('review-requests.index'))

@section('sub-breadcrumb','Review Requests')

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-lg bg-primary border-rounded d-flex align-items-center justify-content-center w-60px h-60px">
                            <i class="fas fa-clipboard-list text-white fs-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $totalRequests ?? 0 }}</h4>
                        <p class="text-muted mb-0">Total Requests</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-lg bg-warning border-rounded d-flex align-items-center justify-content-center w-60px h-60px">
                            <i class="fas fa-clock text-white fs-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $pendingRequests ?? 0 }}</h4>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-lg bg-success border-rounded d-flex align-items-center justify-content-center w-60px h-60px">
                            <i class="fas fa-check text-white fs-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $approvedRequests ?? 0 }}</h4>
                        <p class="text-muted mb-0">Approved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-lg bg-danger border-rounded d-flex align-items-center justify-content-center w-60px h-60px">
                            <i class="fas fa-times text-white fs-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $rejectedRequests ?? 0 }}</h4>
                        <p class="text-muted mb-0">Rejected</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">

        <div class="card-header border-0 pt-6">

            <div class="card-title">

                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search" />
                </div>

            </div>

            @can('create_reviews_requests')
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                        <a href="{{ route('review-requests.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Review Request</a>
                    </div>
                </div>
            @endcan

        </div>
        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>Branch</th>
                        <th>Request Date</th>
                        <th>Scheduled Review</th>
                        <th>Status</th>
                        <th>Review Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviewRequests as $request)
                        <tr>
                            <td>
                                <h6 class="mb-0">{{ $request->branchScore->branch->name }}</h6>
                                <small class="text-muted">{{ $request->branchScore->branch->location }}</small>
                            </td>
                            <td>
                                {{ $request->requested_at?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td>
                                @if($request->scheduled_review_date)
                                    {{ $request->scheduled_review_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not scheduled</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $request->status_color }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                @if($request->reviewed_at)
                                    {{ $request->reviewed_at->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not reviewed</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('view_reviews_requests')
                                    <x-table-icon-link 
                                        :route="route('review-requests.show',$request->id)" 
                                        colorClass="success"
                                        title="View"
                                        iconClasses="fa-solid fa-eye"
                                    />
                                    @endcan
                                    @if($request->status === 'pending')
                                        @can('edit_reviews_requests')
                                        <x-table-icon-link 
                                            :route="route('review-requests.edit',$request->id)" 
                                            colorClass="primary"
                                            title="Edit"
                                            iconClasses="fa-solid fa-pen"
                                        />
                                        @endcan
                                        @can('delete_reviews_requests')
                                        <x-table-icon-link 
                                            :route="route('review-requests.destroy',$request->id)" 
                                            colorClass="danger"
                                            title="Delete"
                                            iconClasses="fa-solid fa-trash"
                                            :isDelete="true"
                                        />
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                <h5>No Review Requests Found</h5>
                                <p>You haven't submitted any review requests yet.</p>
                                @can('create_reviews_requests')
                                    <a href="{{ route('review-requests.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create First Request
                                    </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('_partials.dataTable-script')
@endsection