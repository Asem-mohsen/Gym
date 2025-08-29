@extends('layout.admin.master')

@section('title', $membership->name . ' Membership Details')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Memberships')
@section('sub-breadcrumb-link', route('membership.index'))

@section('content')

<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!-- Membership Profile Card -->
    <div class="col-xl-4">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>Membership Plan</h2>
                </div>
                <div class="card-toolbar">
                    @can('edit_memberships')
                    <a href="{{ route('membership.edit', $membership) }}" class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-pencil fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Edit
                    </a>
                    @endcan
                </div>
            </div>
            
            <div class="card-body pt-2">
                <div class="text-center mb-5">
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <div class="symbol-label fs-1 fw-semibold bg-light-primary text-primary">
                            <i class="ki-duotone ki-user-group fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    
                    <h3 class="fs-2hx fw-bold text-dark">{{ $membership->name }}</h3>
                    <div class="fs-6 fw-semibold text-muted mb-3">{{ $membership->subtitle }}</div>
                    
                    <div class="d-flex justify-content-center">
                        <span class="fs-1 fw-bold text-primary">{{ $membership->price }} EGP</span>
                    </div>
                </div>
                
                <div class="separator my-10"></div>
                
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center py-3">
                        <div class="symbol symbol-35px me-3">
                            <div class="symbol-label bg-light">
                                <i class="ki-duotone ki-calendar fs-2 text-gray-600">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-800">{{ $membership->period }}</span>
                            <span class="fs-7 fw-semibold text-muted">Period</span>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center py-3">
                        <div class="symbol symbol-35px me-3">
                            <div class="symbol-label bg-light">
                                <i class="ki-duotone ki-star fs-2 text-gray-600">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-800">{{ $membership->features->count() }}</span>
                            <span class="fs-7 fw-semibold text-muted">Features</span>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center py-3">
                        <div class="symbol symbol-35px me-3">
                            <div class="symbol-label bg-light">
                                <i class="ki-duotone ki-calendar fs-2 text-gray-600">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-800">{{ $membership->created_at->format('M d, Y') }}</span>
                            <span class="fs-7 fw-semibold text-muted">Created</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Membership Details -->
    <div class="col-xl-8">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>Membership Information</h2>
                </div>
            </div>
            
            <div class="card-body pt-2">
                <!-- Basic Information -->
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Basic Information</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Name:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $membership->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Price:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $membership->price }} EGP</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Period:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $membership->period }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Status:</span>
                                @if($membership->status)
                                    <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                                @else
                                    <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                @if($membership->subtitle)
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Description</h4>
                    <p class="fs-6 text-gray-600">{{ $membership->subtitle }}</p>
                </div>
                @endif
                
                <!-- Features -->
                @if($membership->features->count() > 0)
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Features ({{ $membership->features->count() }})</h4>
                    <div class="row g-3">
                        @foreach($membership->features as $feature)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-25px me-3">
                                    <div class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-check fs-2 text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <span class="fs-6 fw-semibold text-gray-800">{{ $feature->getTranslation('name', app()->getLocale()) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Additional Information -->
                @if($membership->description)
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Additional Information</h4>
                    <p class="fs-6 text-gray-600">{{ $membership->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Actions Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Actions</h3>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            @can('edit_memberships')
            <a href="{{ route('membership.edit', $membership) }}" class="btn btn-primary">
                <i class="ki-duotone ki-pencil fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Edit Membership
            </a>
            @endcan
            
            @can('delete_memberships')
            <form action="{{ route('membership.destroy', $membership) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this membership?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="ki-duotone ki-trash fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Delete Membership
                </button>
            </form>
            @endcan
            
            <a href="{{ route('membership.index') }}" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Back to Memberships
            </a>
        </div>
    </div>
</div>

@endsection
