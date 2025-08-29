@extends('layout.admin.master')

@section('title', $service->getTranslation('name', 'en'))

@section('main-breadcrumb', 'Service')
@section('main-breadcrumb-link', route('services.index'))

@section('sub-breadcrumb','Show Service')

@section('content')

<div class="row">
    <!-- Service Information Card -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h3 class="card-title">Service Information</h3>
                    <div class="d-flex gap-2">
                        @can('edit_services')
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        @endcan
                        @can('delete_services')
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Basic Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name (English):</strong></td>
                                <td>{{ $service->getTranslation('name', 'en') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Name (Arabic):</strong></td>
                                <td>{{ $service->getTranslation('name', 'ar') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Description (English):</strong></td>
                                <td>{{ $service->getTranslation('description', 'en') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Description (Arabic):</strong></td>
                                <td>{{ $service->getTranslation('description', 'ar') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Duration:</strong></td>
                                <td>
                                    @if($service->duration > 0)
                                        {{ $service->duration }} minutes
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Price:</strong></td>
                                <td>
                                    @if($service->price > 0)
                                        {{ number_format($service->price, 2) }} EGP
                                    @else
                                        <span class="text-success">Free</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Booking Configuration</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Booking Type:</strong></td>
                                <td>
                                    @switch($service->booking_type)
                                        @case('unbookable')
                                            <span class="badge badge-light-info">Unbookable</span>
                                            @break
                                        @case('free_booking')
                                            <span class="badge badge-light-success">Free Booking</span>
                                            @break
                                        @case('paid_booking')
                                            <span class="badge badge-light-warning">Paid Booking</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($service->is_available)
                                        <span class="badge badge-light-success">Available</span>
                                    @else
                                        <span class="badge badge-light-danger">Unavailable</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Sort Order:</strong></td>
                                <td>{{ $service->sort_order }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $service->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Updated:</strong></td>
                                <td>{{ $service->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Image Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Service Image</h3>
            </div>
            <div class="card-body text-center">
                @if($service->getFirstMediaUrl('service_image'))
                    <img src="{{ $service->getFirstMediaUrl('service_image') }}" 
                         alt="{{ $service->getTranslation('name', 'en') }}" 
                         class="img-fluid rounded" 
                         style="max-height: 300px; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                        <div class="text-muted">
                            <i class="fa fa-image fa-3x mb-3"></i>
                            <p>No image available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Branches Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Available Branches</h3>
            </div>
            <div class="card-body">
                @if($service->branches->count() > 0)
                    <div class="row">
                        @foreach($service->branches as $branch)
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3">
                                    <h6>{{ $branch->getTranslation('name', 'en') }}</h6>
                                    <p class="text-muted mb-1">{{ $branch->getTranslation('name', 'ar') }}</p>
                                    <p class="text-muted mb-0">{{ $branch->getTranslation('location', 'en') }}</p>
                                    <small class="text-muted">{{ $branch->type }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fa fa-building fa-2x mb-3"></i>
                        <p>This service is available in all branches</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Gallery Card -->
@if($service->galleries->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Gallery</h3>
                </div>
                <div class="card-body">
                    @foreach($service->galleries as $gallery)
                        <div class="mb-4">
                            <h5>{{ $gallery->title }}</h5>
                            @if($gallery->description)
                                <p class="text-muted">{{ $gallery->description }}</p>
                            @endif
                            
                            @if($gallery->getMedia('gallery_images')->count() > 0)
                                <div class="row">
                                    @foreach($gallery->getMedia('gallery_images') as $media)
                                        <div class="col-md-3 col-sm-4 col-6 mb-3">
                                            <div class="gallery-item">
                                                <img src="{{ $media->getUrl() }}" 
                                                     alt="{{ $media->getCustomProperty('alt_text', $media->file_name) }}"
                                                     class="img-fluid rounded" 
                                                     style="height: 150px; width: 100%; object-fit: cover;">
                                                <div class="gallery-item-overlay">
                                                    <small class="text-white">{{ $media->file_name }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fa fa-images fa-2x mb-2"></i>
                                    <p>No images in this gallery</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Statistics Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Service Statistics</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fa fa-calendar fa-2x text-white"></i>
                            </div>
                            <h4 class="mt-2">{{ $service->bookings->count() }}</h4>
                            <p class="text-muted">Total Bookings</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fa fa-building fa-2x text-white"></i>
                            </div>
                            <h4 class="mt-2">{{ $service->branches->count() }}</h4>
                            <p class="text-muted">Available Branches</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fa fa-images fa-2x text-white"></i>
                            </div>
                            <h4 class="mt-2">{{ $service->galleries->count() }}</h4>
                            <p class="text-muted">Galleries</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fa fa-tag fa-2x text-white"></i>
                            </div>
                            <h4 class="mt-2">{{ $service->offers->count() }}</h4>
                            <p class="text-muted">Active Offers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.gallery-item-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    padding: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-item-overlay {
    opacity: 1;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.table-borderless td:first-child {
    font-weight: 600;
    width: 40%;
}
</style>
@endsection
