@extends('layout.admin.master')

@section('title' , 'Edit ' . $branch->name)

@section('main-breadcrumb', 'Branch')
@section('main-breadcrumb-link', route('branches.index'))

@section('sub-breadcrumb','Edit Branch')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('branches.update' , $branch->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name_en" class="required form-label">Branch Name (English)</label>
                    <input type="text" name="name[en]" id="name_en"  value="{{$branch->getTranslation('name','en')}}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="service_ar" class="required form-label">Branch Name (Arabic)</label>
                    <input type="text" name="name[ar]" id="name_ar"  value="{{$branch->getTranslation('name','ar')}}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="manager_id" class="required form-label">Manager</label>
                    @php
                        $options = [];
                        foreach($users as $user){
                            $options[] = [
                                'value' => $user->id,
                                'label' => $user->name
                            ];
                        }
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'manager_id',
                        'id' => 'manager_id',
                        'selectedValue' => $branch->manager_id
                    ])
                    <small class="form-text text-muted">Managers are admins who can manage the branch. You can assign a new manager by creating a new admin first. </small>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="type" class="required form-label">Type</label>
                    @php
                        $options = [
                            ['value' => 'mix', 'label' => 'Mix'],
                            ['value' => 'ladies', 'label' => 'Ladies'],
                            ['value' => 'men', 'label' => 'Men'],
                        ];
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'type',
                        'id' => 'type',
                        'selectedValue' => $branch->type
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="location_en" class="required form-label">Location (English)</label>
                    <textarea class="form-control form-control-solid required" name="location[en]" id="location_en" required>{{$branch->getTranslation('location','en')}}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="location_ar" class="required form-label">Location (Arabic)</label>
                    <textarea class="form-control form-control-solid required" name="location[ar]" id="location_ar" required>{{$branch->getTranslation('location','ar')}}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" id="image" name="image" class="form-control form-control-solid" accept="image/*"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="size" class="required form-label">Size</label>
                    <input type="size" id="size" value="{{ $branch->size }}" name="size" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="facebook_url" class="required form-label">Facebook URL</label>
                    <input type="text" id="facebook_url" value="{{ $branch->facebook_url }}" name="facebook_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="instagram_url" class="form-label">Instagram URL</label>
                    <input type="text" id="instagram_url" value="{{ $branch->instagram_url }}" name="instagram_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="x_url" class="form-label">X URL</label>
                    <input type="text" id="x_url" value="{{ $branch->x_url }}" name="x_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_visible" name="is_visible" value="1" {{ $branch->is_visible ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_visible">
                            <strong>Branch Visibility</strong>
                        </label>
                        <div class="form-text text-muted">When disabled, this branch will not appear for users in class selection, service booking, or any user-facing features</div>
                    </div>
                </div>
                <div class="mb-10 col-md-6">
                    <label class="required form-label">Phone Numbers</label>
                    <div id="phone-repeater" data-kt-repeater="list">
                        <div data-repeater-list="phones">
                            @if($existingPhones && count($existingPhones) > 0)
                                @foreach($existingPhones as $index => $phone)
                                    <div data-repeater-item class="form-group row mb-3">
                                        <div class="col-md-10">
                                            <input type="text" name="phones" class="form-control form-control-solid required" placeholder="Phone Number" value="{{ $phone }}" required/>
                                        </div>
                                        <div class="col-md-2">
                                            @if($index > 0)
                                                <button type="button" data-repeater-delete class="btn btn-md btn-light-danger">
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div data-repeater-item class="form-group row mb-3">
                                    <div class="col-md-10">
                                        <input type="text" name="phones" class="form-control form-control-solid required" placeholder="Phone Number" required/>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" data-repeater-delete class="btn btn-md btn-light-danger">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="button" data-repeater-create class="btn btn-light-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                Add Phone
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Coordinates Section -->
                <div class="mb-10 col-md-12">
                    <h4 class="mb-4">Location Coordinates</h4>
                    <p class="text-muted mb-4">Add precise coordinates for better location-based sorting and distance calculation.</p>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="map_url" class="form-label">Map URL</label>
                    <input type="url" id="map_url" value="{{ $branch->map_url }}" name="map_url" class="form-control form-control-solid" placeholder="https://maps.google.com/..."/>
                    <small class="form-text text-muted">Share the Google Maps or any map service URL for the branch location</small>
                </div>

                <div class="mt-8 col-md-6 d-flex flex-column w-fit">
                    <button type="button" id="getCoordinatesBtn" class="btn btn-light-primary">
                        <i class="fas fa-map-marker-alt me-2"></i>Get Coordinates from Map URL
                    </button>
                    <small class="form-text text-muted ms-2">Click this button if you've entered a Google Maps URL above</small>
                </div>

                <div class="mb-10 col-md-4">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="number" id="latitude" value="{{ old('latitude', $branch->latitude) }}" name="latitude" class="form-control form-control-solid" step="any" placeholder="e.g., 30.0444"/>
                    <small class="form-text text-muted">Enter the latitude coordinate (e.g., 30.0444 for Cairo)</small>
                </div>
                
                <div class="mb-10 col-md-4">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="number" id="longitude" value="{{ old('longitude', $branch->longitude) }}" name="longitude" class="form-control form-control-solid" step="any" placeholder="e.g., 31.2357"/>
                    <small class="form-text text-muted">Enter the longitude coordinate (e.g., 31.2357 for Cairo)</small>
                </div>
                
                <div class="mb-10 col-md-4">
                    <label for="city" class="form-label">City</label>
                    <input type="text" id="city" value="{{ old('city', $branch->city) }}" name="city" class="form-control form-control-solid" placeholder="e.g., Cairo"/>
                    <small class="form-text text-muted">Enter the city name for better location matching</small>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="region" class="form-label">Region/Governorate</label>
                    <input type="text" id="region" value="{{ old('region', $branch->region) }}" name="region" class="form-control form-control-solid" placeholder="e.g., Cairo Governorate"/>
                    <small class="form-text text-muted">Enter the region or governorate name</small>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" id="country" value="{{ old('country', $branch->country ?? 'Egypt') }}" name="country" class="form-control form-control-solid" placeholder="e.g., Egypt"/>
                    <small class="form-text text-muted">Enter the country name</small>
                </div>
                
                <div class="mb-10 col-md-12">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>How to get coordinates:</h6>
                        <ol class="mb-0">
                            <li>Go to <a href="https://maps.google.com" target="_blank">Google Maps</a></li>
                            <li>Search for your branch location</li>
                            <li>Right-click on the exact location and select "What's here?"</li>
                            <li>Copy the coordinates that appear in the search box</li>
                            <li>Or use the "Get Coordinates" button below to help you</li>
                        </ol>
                    </div>
                </div>

                <div class="card-footer">
                    @can('edit_branches')
                        <button type="submit" class="btn btn-success">Save</button>
                    @endcan
                    <a href="{{ route('branches.index') }}" class="btn btn-dark">Cancel</a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection

@section('js')
    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    
    @include('admin.branches.assets.scripts')

    <script>
        $('#phone-repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        // Initialize coordinate extraction functionality
        $(document).ready(function() {
            initCoordinateExtraction();
        });
    </script>
@endsection