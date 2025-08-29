@extends('layout.admin.master')

@section('title', 'Edit ' . $service->name)

@section('main-breadcrumb', 'Service')
@section('main-breadcrumb-link', route('services.index'))

@section('sub-breadcrumb','Edit Service')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('services.update', $service->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body row">
                <!-- Basic Information -->
                <div class="col-12 mb-5">
                    <h3 class="card-title">Basic Information</h3>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="service_en" class="required form-label">Service Name (English)</label>
                    <input type="text" value="{{ $service->getTranslation('name','en') }}" id="service_en" name="name[en]" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="service_ar" class="required form-label">Service Name (Arabic)</label>
                    <input type="text" value="{{ $service->getTranslation('name','ar') }}" id="service_ar" name="name[ar]" class="form-control form-control-solid required" required/>
                </div>
          
                <div class="mb-10 col-md-6">
                    <label for="description_en" class="required form-label">Description (English)</label>
                    <textarea id="description_en" name="description[en]" class="form-control form-control-solid required" rows="3" required>{{ $service->getTranslation('description','en') }}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_ar" class="required form-label">Description (Arabic)</label>
                    <textarea id="description_ar" name="description[ar]" class="form-control form-control-solid required" rows="3" required>{{ $service->getTranslation('description','ar') }}</textarea>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="duration" class="required form-label">Duration (minutes)</label>
                    <input type="number" id="duration" value="{{ $service->duration }}" name="duration" class="form-control form-control-solid required" min="0" required/>
                </div>

                <div class="mb-10 col-md-6">
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" id="sort_order" value="{{ $service->sort_order }}" name="sort_order" class="form-control form-control-solid" min="0"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="is_available" class="form-label">Available</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ $service->is_available ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_available">Service is available</label>
                    </div>
                </div>

                <!-- Booking Configuration -->
                <div class="col-12 mb-5 mt-5">
                    <h3 class="card-title">Booking Configuration</h3>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="booking_type" class="required form-label">Booking Type</label>
                    <select id="booking_type" name="booking_type" class="form-control form-control-solid required" required>
                        <option value="">Select Booking Type</option>
                        <option value="unbookable" {{ $service->booking_type == 'unbookable' ? 'selected' : '' }}>Unbookable (No booking required)</option>
                        <option value="free_booking" {{ $service->booking_type == 'free_booking' ? 'selected' : '' }}>Free Booking (No payment required)</option>
                        <option value="paid_booking" {{ $service->booking_type == 'paid_booking' ? 'selected' : '' }}>Paid Booking (Payment required)</option>
                    </select>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="price" class="form-label">Price (EGP)</label>
                    <input type="number" id="price" value="{{ $service->price }}" name="price" class="form-control form-control-solid" min="0" step="0.01"/>
                    <small class="form-text text-muted">Required when booking type is "Paid Booking"</small>
                </div>

                <!-- Branch Assignment -->
                <div class="col-12 mb-5 mt-5">
                    <h3 class="card-title">Branch Assignment</h3>
                </div>
                
                <div class="mb-10 col-12">
                    <label class="form-label">Available Branches</label>
                    <div class="row">
                        @forelse($branches as $branch)
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="branches[]" value="{{ $branch->id }}" id="branch_{{ $branch->id }}" 
                                           {{ in_array($branch->id, $serviceBranches) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="branch_{{ $branch->id }}">
                                        {{ $branch->getTranslation('name', 'en') }} ({{ $branch->getTranslation('name', 'ar') }})
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">No branches available. Please create branches first.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="col-12 mb-5 mt-5">
                    <h3 class="card-title">Gallery (Optional)</h3>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="gallery_title" class="form-label">Gallery Title</label>
                    <input type="text" id="gallery_title" value="{{ $gallery->title ?? '' }}" name="gallery_title" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="gallery_description" class="form-label">Gallery Description</label>
                    <textarea id="gallery_description" name="gallery_description" class="form-control form-control-solid" rows="2">{{ $gallery->description ?? '' }}</textarea>
                </div>
                
                @if($gallery && $gallery->getMedia('gallery_images')->count() > 0)
                    <div class="mb-10 col-12">
                        <label class="form-label">Current Gallery Images</label>
                        <div class="row">
                            @foreach($gallery->getMedia('gallery_images') as $media)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ $media->getUrl() }}" class="card-img-top" alt="Gallery Image" style="height: 150px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <small class="text-muted">{{ $media->file_name }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-10 col-md-6">
                    <label for="image" class="form-label">Main Image</label>
                    <input type="file" id="image" name="image" class="form-control form-control-solid" accept="image/*"/>
                </div>

                <div class="mb-10 col-md-6">
                    <label for="gallery_images" class="form-label">Add New Gallery Images</label>
                    <input type="file" id="gallery_images" name="gallery_images[]" class="form-control form-control-solid" accept="image/*" multiple/>
                    <small class="form-text text-muted">You can select multiple images. Supported formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 2MB per image.</small>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Update Service</button>
                    <a href="{{ route('services.index') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingTypeSelect = document.getElementById('booking_type');
            const priceInput = document.getElementById('price');
            
            function togglePrice() {
                if (bookingTypeSelect.value === 'paid_booking') {
                    priceInput.required = true;
                    priceInput.parentElement.classList.add('required');
                } else {
                    priceInput.required = false;
                    bookingFeeInput.parentElement.classList.remove('required');
                }
            }
            
            bookingTypeSelect.addEventListener('change', togglePrice);
            togglePrice(); // Initial call
        });
    </script>
@endsection
