@extends('layout.admin.master')

@section('title', 'User Details')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Users')
@section('sub-breadcrumb-link', route('users.index'))

@section('content')

<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!-- User Profile Card -->
    <div class="col-xl-4">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>User Profile</h2>
                </div>
                <div class="card-toolbar">
                    @can('edit_users')
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-light-primary">
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
                        @if($user->user_image)
                            <img src="{{ $user->user_image }}" alt="{{ $user->name }}" />
                        @else
                            <div class="symbol-label fs-1 fw-semibold bg-light-primary text-primary">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="fs-2hx fw-bold text-dark">{{ $user->name }}</h3>
                    <div class="fs-6 fw-semibold text-muted mb-3">{{ $user->email }}</div>
                    
                    <div class="d-flex justify-content-center gap-2">
                        @if($user->status)
                            <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                        @else
                            <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
                        @endif
                        
                        @if($user->email_verified_at)
                            <span class="badge badge-light-info fs-7 fw-bold">Verified</span>
                        @else
                            <span class="badge badge-light-warning fs-7 fw-bold">Unverified</span>
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
                            <span class="fs-6 fw-bold text-gray-800">{{ $user->phone }}</span>
                            <span class="fs-7 fw-semibold text-muted">Phone</span>
                        </div>
                    </div>
                    
                    @if($user->gender)
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
                            <span class="fs-6 fw-bold text-gray-800">{{ ucfirst($user->gender) }}</span>
                            <span class="fs-7 fw-semibold text-muted">Gender</span>
                        </div>
                    </div>
                    @endif
                    
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
                            <span class="fs-6 fw-bold text-gray-800">{{ $user->created_at->format('M d, Y') }}</span>
                            <span class="fs-7 fw-semibold text-muted">Joined</span>
                        </div>
                    </div>
                    
                    @if($user->roles->count() > 0)
                    <div class="d-flex align-items-center py-3">
                        <div class="symbol symbol-35px me-3">
                            <div class="symbol-label bg-light">
                                <i class="ki-duotone ki-shield-tick fs-2 text-gray-600">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-800">{{ $user->roles->first()->name }}</span>
                            <span class="fs-7 fw-semibold text-muted">Role</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Details -->
    <div class="col-xl-8">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>User Information</h2>
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
                                <span class="fs-6 fw-bold text-gray-800">{{ $user->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Email:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $user->email }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Phone:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $user->phone }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Status:</span>
                                @if($user->status)
                                    <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                                @else
                                    <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Address -->
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Address</h4>
                    <p class="fs-6 text-gray-600">{{ $user->address }}</p>
                </div>
                
                <!-- Membership Information -->
                @if(isset($user->membership))
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Membership Information</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Membership:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $user->membership->name }}</span>
                            </div>
                        </div>
                        @if(isset($amountPaid))
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Total Paid:</span>
                                <span class="fs-6 fw-bold text-gray-800">EGP {{ $amountPaid }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Permissions -->
                @if($user->roles->count() > 0 && $user->permissions->count() > 0)
                    <div class="mb-8">
                        <h4 class="fs-5 fw-bold text-gray-800 mb-3">Permissions</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($user->getAllPermissions() as $permission)
                                <span class="badge badge-light-primary fs-7">{{ $permission->name }}</span>
                            @endforeach
                        </div>
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
            @can('edit_users')
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                    <i class="ki-duotone ki-pencil fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Edit User
                </a>
           
            
                @if(!$user->has_set_password)
                    <form action="{{ route('users.resend-onboarding-email', $user) }}" method="POST" class="d-inline">
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
            @endcan
            
            @can('delete_users')
            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="ki-duotone ki-trash fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Delete User
                </button>
            </form>
            @endcan
            
            <a href="{{ route('users.index') }}" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Back to Users
            </a>
        </div>
    </div>
</div>

@endsection
