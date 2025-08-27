@extends('layout.admin.master')

@section('title', 'Trainer Details')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Trainers')
@section('sub-breadcrumb-link', route('trainers.index'))

@section('content')

<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!-- Trainer Profile Card -->
    <div class="col-xl-4">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>Trainer Profile</h2>
                </div>
                <div class="card-toolbar">
                    @can('edit_trainers')
                    <a href="{{ route('trainers.edit', $trainer) }}" class="btn btn-sm btn-light-primary">
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
                        @if($trainer->user_image)
                            <img src="{{ $trainer->user_image }}" alt="{{ $trainer->name }}" />
                        @else
                            <div class="symbol-label fs-1 fw-semibold bg-light-primary text-primary">
                                {{ strtoupper(substr($trainer->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="fs-2hx fw-bold text-dark">{{ $trainer->name }}</h3>
                    <div class="fs-6 fw-semibold text-muted mb-3">{{ $trainer->email }}</div>
                    
                    <div class="d-flex justify-content-center">
                        @if($trainer->status)
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
                            <span class="fs-6 fw-bold text-gray-800">{{ $trainer->phone }}</span>
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
                            <span class="fs-6 fw-bold text-gray-800">{{ ucfirst($trainer->gender) }}</span>
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
                            <span class="fs-6 fw-bold text-gray-800">{{ $trainer->created_at->format('M d, Y') }}</span>
                            <span class="fs-7 fw-semibold text-muted">Joined</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Trainer Details -->
    <div class="col-xl-8">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <h2>Trainer Information</h2>
                </div>
            </div>
            
            <div class="card-body pt-2">
                <!-- Address -->
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Address</h4>
                    <p class="fs-6 text-gray-600">{{ $trainer->address }}</p>
                </div>
                
                <!-- Trainer Information -->
                @if($trainer->trainerInformation)
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Physical Information</h4>
                    <div class="row g-3">
                        @if($trainer->trainerInformation->weight)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Weight:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $trainer->trainerInformation->weight }} kg</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($trainer->trainerInformation->height)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Height:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ $trainer->trainerInformation->height }} cm</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($trainer->trainerInformation->date_of_birth)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="fs-6 fw-semibold text-muted me-2">Date of Birth:</span>
                                <span class="fs-6 fw-bold text-gray-800">{{ \Carbon\Carbon::parse($trainer->trainerInformation->date_of_birth)->format('M d, Y') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($trainer->trainerInformation->brief_description)
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Description</h4>
                    <p class="fs-6 text-gray-600">{{ $trainer->trainerInformation->brief_description }}</p>
                </div>
                @endif
                
                <!-- Social Media Links -->
                @if($trainer->trainerInformation->facebook_url || $trainer->trainerInformation->twitter_url || $trainer->trainerInformation->instagram_url || $trainer->trainerInformation->youtube_url)
                <div class="mb-8">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Social Media</h4>
                    <div class="d-flex flex-wrap gap-2">
                        @if($trainer->trainerInformation->facebook_url)
                        <a href="{{ $trainer->trainerInformation->facebook_url }}" target="_blank" class="btn btn-sm btn-light-primary">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                        @endif
                        
                        @if($trainer->trainerInformation->twitter_url)
                        <a href="{{ $trainer->trainerInformation->twitter_url }}" target="_blank" class="btn btn-sm btn-light-info">
                            <i class="fab fa-twitter"></i>
                            Twitter
                        </a>
                        @endif
                        
                        @if($trainer->trainerInformation->instagram_url)
                        <a href="{{ $trainer->trainerInformation->instagram_url }}" target="_blank" class="btn btn-sm btn-light-danger">
                            <i class="fab fa-instagram"></i>
                            Instagram
                        </a>
                        @endif
                        
                        @if($trainer->trainerInformation->youtube_url)
                        <a href="{{ $trainer->trainerInformation->youtube_url }}" target="_blank" class="btn btn-sm btn-light-danger">
                            <i class="fab fa-youtube"></i>
                            YouTube
                        </a>
                        @endif
                    </div>
                </div>
                @endif
                @else
                <div class="text-center py-10">
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <div class="symbol-label bg-light">
                            <i class="ki-duotone ki-user fs-2 text-gray-400">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <h4 class="fs-5 fw-bold text-gray-800 mb-2">No Trainer Information</h4>
                    <p class="fs-6 text-gray-600">This trainer hasn't provided additional information yet.</p>
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
            @can('edit_trainers')
                <a href="{{ route('trainers.edit', $trainer) }}" class="btn btn-primary">
                    <i class="ki-duotone ki-pencil fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Edit Trainer
                </a>
                
                
                @if(!$trainer->has_set_password)
                <form action="{{ route('trainers.resend-onboarding-email', $trainer) }}" method="POST" class="d-inline">
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
            
            @can('delete_trainers')
                <form action="{{ route('trainers.destroy', $trainer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this trainer?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ki-duotone ki-trash fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Delete Trainer
                    </button>
                </form>
            @endcan
            
            <a href="{{ route('trainers.index') }}" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Back to Trainers
            </a>
        </div>
    </div>
</div>

@endsection
