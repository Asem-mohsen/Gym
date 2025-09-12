@extends('layout.admin.master')

@section('title', 'Admin Details')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Admins')
@section('sub-breadcrumb-link', route('admins.index'))

@section('content')

<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!-- Admin Profile Card -->
    <div class="col-xl-4">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>Admin Profile</h2>
                </div>
                <div class="card-toolbar">
                    @can('edit_admins')
                    <a href="{{ route('admins.edit', $admin) }}" class="btn btn-sm btn-light-primary">
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
                        @if($admin->user_image)
                            <img src="{{ $admin->user_image }}" alt="{{ $admin->name }}" />
                        @else
                            <div class="symbol-label fs-1 fw-semibold bg-light-primary text-primary">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="fs-2hx fw-bold text-dark">{{ $admin->name }}</h3>
                    <div class="fs-6 fw-semibold text-muted mb-3">{{ $admin->email }}</div>
                    
                    <div class="d-flex justify-content-center">
                        @if($admin->status)
                            <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                        @else
                            <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
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
                            <span class="fs-6 fw-bold text-gray-800">{{ $admin->phone }}</span>
                            <span class="fs-7 fw-semibold text-muted">Phone</span>
                        </div>
                    </div>
                    
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
                            <span class="fs-6 fw-bold text-gray-800">{{ $admin->roles->first()?->name ?? 'No Role' }}</span>
                            <span class="fs-7 fw-semibold text-muted">Role</span>
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
                            <span class="fs-6 fw-bold text-gray-800">{{ $admin->created_at->format('M d, Y') }}</span>
                            <span class="fs-7 fw-semibold text-muted">Joined</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admin Details -->
    <div class="col-xl-8">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>Admin Information</h2>
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
                                <span class="fs-6 fw-bold text-gray-800">{{ $admin->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Email:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $admin->email }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Phone:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $admin->phone }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Branch:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $admin->assignedBranches->pluck('name')->implode(', ') }}</span>
                                @if($admin->assignedBranches->count() == 0)
                                    <span class="badge badge-light-danger fs-7 fw-bold">No Branch</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Status:</span>
                                @if($admin->status)
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
                    <p class="fs-6 text-gray-600">{{ $admin->address }}</p>
                </div>
                
                <!-- Permissions -->
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Permissions</h4>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($admin->getAllPermissions() as $permission)
                            <span class="badge badge-light-primary fs-7">{{ $permission->name }}</span>
                        @endforeach
                    </div>
                </div>
                
                <!-- Admin Photos -->
                @if($admin->photos->count() > 0)
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Admin Photos</h4>
                    <div class="row g-3">
                        @foreach($admin->photos as $photo)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="card card-flush h-100">
                                <div class="card-body p-2">
                                    <div class="position-relative">
                                        <img src="{{ $photo->thumbnail_url }}" 
                                             alt="{{ $photo->title }}" 
                                             class="w-100 rounded" 
                                             style="height: 120px; object-fit: cover;"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#photoModal{{ $photo->id }}">
                                        
                                        @if(!$photo->is_public)
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <span class="badge badge-light-warning fs-8">Private</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if($photo->title)
                                    <div class="mt-2">
                                        <h6 class="fs-7 fw-bold text-gray-800 mb-0">{{ $photo->title }}</h6>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-1">
                                        <small class="text-muted">{{ $photo->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Photo Modal -->
                        <div class="modal fade" id="photoModal{{ $photo->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $photo->title ?: 'Admin Photo' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ $photo->photo_url }}" 
                                             alt="{{ $photo->title }}" 
                                             class="img-fluid rounded">
                                        
                                        @if($photo->description)
                                        <p class="mt-3 text-muted">{{ $photo->description }}</p>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <span class="badge badge-light-{{ $photo->is_public ? 'success' : 'warning' }}">
                                                {{ $photo->is_public ? 'Public' : 'Private' }}
                                            </span>
                                            <small class="text-muted ms-2">Uploaded: {{ $photo->created_at->format('M d, Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($admin->photos->count() > 8)
                        <div class="text-center mt-3">
                            <button class="btn btn-sm btn-light-primary" type="button" data-bs-toggle="collapse" data-bs-target="#allPhotos" aria-expanded="false">
                                View All Photos ({{ $admin->photos->count() }})
                            </button>
                        </div>
                        
                        <div class="collapse mt-3" id="allPhotos">
                            <div class="row g-3">
                                @foreach($admin->photos->skip(8) as $photo)
                                <div class="col-md-3 col-sm-4 col-6">
                                    <div class="card card-flush h-100">
                                        <div class="card-body p-2">
                                            <div class="position-relative">
                                                <img src="{{ $photo->thumbnail_url }}" 
                                                        alt="{{ $photo->title }}" 
                                                        class="w-100 rounded" 
                                                        style="height: 120px; object-fit: cover;"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#photoModal{{ $photo->id }}">
                                                
                                                @if(!$photo->is_public)
                                                <div class="position-absolute top-0 end-0 m-1">
                                                    <span class="badge badge-light-warning fs-8">Private</span>
                                                </div>
                                                @endif
                                            </div>
                                            
                                            @if($photo->title)
                                            <div class="mt-2">
                                                <h6 class="fs-7 fw-bold text-gray-800 mb-0">{{ $photo->title }}</h6>
                                            </div>
                                            @endif
                                            
                                            <div class="mt-1">
                                                <small class="text-muted">{{ $photo->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
            @can('edit_admins')
            <a href="{{ route('admins.edit', $admin) }}" class="btn btn-primary">
                <i class="ki-duotone ki-pencil fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Edit Admin
            </a>
            @endcan
            
            @can('delete_admins')
            <form action="{{ route('admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this admin?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="ki-duotone ki-trash fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Delete Admin
                </button>
            </form>
            @endcan
            
            <a href="{{ route('admins.index') }}" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Back to Admins
            </a>
        </div>
    </div>
</div>

@endsection
