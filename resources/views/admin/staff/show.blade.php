@extends('layout.admin.master')

@section('title', $staff->name . ' - Staff Details')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Staff')
@section('sub-breadcrumb-link', route('staff.index'))

@section('content')

<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!-- Staff Profile Card -->
    <div class="col-xl-4">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>Staff Profile</h2>
                </div>
                <div class="card-toolbar">
                    @can('edit_staff')
                        <a href="{{ route('staff.edit', $staff) }}" class="btn btn-sm btn-light-primary">
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
                        @if($staff->getFirstMediaUrl('user_images'))
                            <img src="{{ $staff->getFirstMediaUrl('user_images') }}" alt="{{ $staff->name }}" class="symbol-label" />
                        @else
                            <div class="symbol-label fs-1 fw-semibold bg-light-primary text-primary">
                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="fs-2hx fw-bold text-dark">{{ $staff->name }}</h3>
                    <div class="fs-6 fw-semibold text-muted mb-3">{{ $staff->email }}</div>
                    
                    <div class="d-flex justify-content-center">
                        @if($staff->status)
                            <span class="badge badge-light-success fs-7 fw-bold">Active Staff</span>
                        @else
                            <span class="badge badge-light-danger fs-7 fw-bold">Inactive Staff</span>
                        @endif
                    </div>
                </div>
                
                <div class="separator my-10"></div>
                
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center py-3">
                        <div class="symbol symbol-35px me-3">
                            <div class="symbol-label bg-light">
                                <i class="ki-duotone ki-phone fs-2 text-gray-600">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-800">{{ $staff->phone }}</span>
                            <span class="fs-7 fw-semibold text-muted">Phone</span>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center py-3">
                        <div class="symbol symbol-35px me-3">
                            <div class="symbol-label bg-light">
                                <i class="ki-duotone ki-user fs-2 text-gray-600">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-800">{{ ucfirst($staff->gender ?? 'Not specified') }}</span>
                            <span class="fs-7 fw-semibold text-muted">Gender</span>
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
                            <span class="fs-6 fw-bold text-gray-800">{{ $staff->created_at->format('M d, Y') }}</span>
                            <span class="fs-7 fw-semibold text-muted">Joined</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Staff Details -->
    <div class="col-xl-8">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>Staff Information</h2>
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
                                <span class="fs-6 fw-bold text-gray-800">{{ $staff->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Email:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $staff->email }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Phone:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $staff->phone }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Status:</span>
                                @if($staff->status)
                                    <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                                @else
                                    <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Address Information -->
                @if($staff->address)
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Address Information</h4>
                    <p class="fs-6 text-gray-600">{{ $staff->address }}</p>
                </div>
                @endif
                
                <!-- Roles and Permissions -->
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Roles and Permissions</h4>
                    <div class="row g-3">
                        @if($staff->roles->count() > 0)
                            <div class="col-12">
                                <h5 class="fs-6 fw-bold text-gray-700 mb-2">Assigned Roles:</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($staff->roles as $role)
                                        <span class="badge badge-light-primary fs-7 fw-bold">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <p class="text-muted">No roles assigned</p>
                            </div>
                        @endif
                        
                        @if($staff->permissions->count() > 0)
                            <div class="col-12">
                                <h5 class="fs-6 fw-bold text-gray-700 mb-2">Direct Permissions:</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($staff->permissions as $permission)
                                        <span class="badge badge-light-info fs-7 fw-bold">{{ $permission->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Account Information -->
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Account Information</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Account Type:</span>
                                <span class="fs-6 fw-bold text-gray-800">Staff Member</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Last Updated:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $staff->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
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
            @can('edit_staff')
            <a href="{{ route('staff.edit', $staff) }}" class="btn btn-primary">
                <i class="ki-duotone ki-pencil fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Edit Staff Member
            </a>
            @endcan
            
            @if(!$staff->has_set_password)
            <form action="{{ route('staff.resend-onboarding-email', $staff) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="ki-duotone ki-message-text-2 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Resend Onboarding Email
                </button>
            </form>
            @endif
            
            @can('delete_staff')
            <form action="{{ route('staff.destroy', $staff) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="ki-duotone ki-trash fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Delete Staff Member
                </button>
            </form>
            @endcan
            
            <a href="{{ route('staff.index') }}" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Back to Staff
            </a>
        </div>
    </div>
</div>

@endsection
