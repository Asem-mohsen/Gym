@extends('layout.admin.master')

@section('title', 'Branch Details - ' . $branch->name)

@section('main-breadcrumb', 'Branches')
@section('main-breadcrumb-link', route('branches.index'))

@section('sub-breadcrumb', 'Branch Details')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Branch Information</h3>
            <div class="card-toolbar">
                <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-primary">
                    <i class="ki-duotone ki-pencil fs-2"></i>
                    Edit Branch
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Branch Name (English)</label>
                        <p class="form-control-static">{{ $branch->getTranslation('name', 'en') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Branch Name (Arabic)</label>
                        <p class="form-control-static">{{ $branch->getTranslation('name', 'ar') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Manager</label>
                        <p class="form-control-static">{{ $branch->manager->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Type</label>
                        <p class="form-control-static">
                            <span class="badge badge-light-primary">{{ ucfirst($branch->type) }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Location (English)</label>
                        <p class="form-control-static">{{ $branch->getTranslation('location', 'en') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Location (Arabic)</label>
                        <p class="form-control-static">{{ $branch->getTranslation('location', 'ar') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Size</label>
                        <p class="form-control-static">{{ $branch->size }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Created At</label>
                        <p class="form-control-static">{{ $branch->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
                
                <!-- Social Media Links -->
                <div class="col-md-12">
                    <h4 class="mb-4">Social Media Links</h4>
                </div>
                <div class="col-md-4">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Facebook URL</label>
                        @if($branch->facebook_url)
                            <p class="form-control-static">
                                <a href="{{ $branch->facebook_url }}" target="_blank" class="text-primary">
                                    <i class="fab fa-facebook me-2"></i>{{ $branch->facebook_url }}
                                </a>
                            </p>
                        @else
                            <p class="form-control-static text-muted">Not provided</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Instagram URL</label>
                        @if($branch->instagram_url)
                            <p class="form-control-static">
                                <a href="{{ $branch->instagram_url }}" target="_blank" class="text-primary">
                                    <i class="fab fa-instagram me-2"></i>{{ $branch->instagram_url }}
                                </a>
                            </p>
                        @else
                            <p class="form-control-static text-muted">Not provided</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-5">
                        <label class="form-label fw-bold">X (Twitter) URL</label>
                        @if($branch->x_url)
                            <p class="form-control-static">
                                <a href="{{ $branch->x_url }}" target="_blank" class="text-primary">
                                    <i class="fab fa-x-twitter me-2"></i>{{ $branch->x_url }}
                                </a>
                            </p>
                        @else
                            <p class="form-control-static text-muted">Not provided</p>
                        @endif
                    </div>
                </div>
                
                <!-- Map URL -->
                <div class="col-md-12">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Map Location</label>
                        @if($branch->map_url)
                            <p class="form-control-static">
                                <a href="{{ $branch->map_url }}" target="_blank" class="btn btn-primary">
                                    <i class="ki-duotone ki-map fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    View Branch Location on Map
                                </a>
                            </p>
                        @else
                            <p class="form-control-static text-muted">Map location not provided</p>
                        @endif
                    </div>
                </div>
                
                <!-- Phone Numbers -->
                <div class="col-md-12">
                    <h4 class="mb-4">Contact Information</h4>
                </div>
                <div class="col-md-12">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Phone Numbers</label>
                        @if($branch->phones && $branch->phones->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($branch->phones as $phone)
                                    <span class="badge badge-light-info fs-6">{{ $phone->phone_number }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="form-control-static text-muted">No phone numbers provided</p>
                        @endif
                    </div>
                </div>
                
                <!-- Branch Image -->
                @if($branch->getFirstMediaUrl('branch_images'))
                    <div class="col-md-12">
                        <div class="mb-5">
                            <label class="form-label fw-bold">Branch Image</label>
                            <div class="mt-2">
                                <img src="{{ $branch->getFirstMediaUrl('branch_images') }}" 
                                     alt="Branch Image" 
                                     class="img-fluid rounded" 
                                     style="max-width: 300px;">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
