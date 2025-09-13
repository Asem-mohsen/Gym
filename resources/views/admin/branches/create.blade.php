@extends('layout.admin.master')

@section('title', 'Create Branch')

@section('main-breadcrumb', 'Branch')
@section('main-breadcrumb-link', route('branches.index'))

@section('sub-breadcrumb','Create Branch')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('branches.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name_en" class="required form-label">Branch Name (English)</label>
                    <input type="text" name="name[en]" id="name_en" value="{{ old('name.en') }}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="service_ar" class="required form-label">Branch Name (Arabic)</label>
                    <input type="text" name="name[ar]" id="name_ar" value="{{ old('name.ar') }}" class="form-control form-control-solid required" required/>
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
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="location_en" class="required form-label">Location (English)</label>
                    <textarea class="form-control form-control-solid required" name="location[en]" id="location_en" required>{{ old('location.en') }}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="location_ar" class="required form-label">Location (Arabic)</label>
                    <textarea class="form-control form-control-solid required" name="location[ar]" id="location_ar" required>{{ old('location.ar') }}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" id="image" name="image" class="form-control form-control-solid" accept="image/*"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="size" class="required form-label">Size</label>
                    <input type="text" id="size" value="{{ old('size') }}" name="size" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="facebook_url" class="required form-label">Facebook URL</label>
                    <input type="text" id="facebook_url" value="{{ old('facebook_url') }}" name="facebook_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="instagram_url" class="form-label">Instagram URL</label>
                    <input type="text" id="instagram_url" value="{{ old('instagram_url') }}" name="instagram_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="x_url" class="form-label">X URL</label>
                    <input type="text" id="x_url" value="{{ old('x_url') }}" name="x_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_visible">
                            <strong>Branch Visibility</strong>
                        </label>
                        <div class="form-text text-muted">When disabled, this branch will not appear for users in class selection, service booking, or any user-facing features</div>
                    </div>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="map_url" class="form-label">Map URL</label>
                    <input type="url" id="map_url" value="{{ old('map_url') }}" name="map_url" class="form-control form-control-solid" placeholder="https://maps.google.com/..."/>
                    <small class="form-text text-muted">Share the Google Maps or any map service URL for the branch location</small>
                </div>
                
                <div class="mt-8 col-md-6 d-flex flex-column w-fit">
                    <button type="button" id="getCoordinatesBtn" class="btn btn-light-primary">
                        <i class="fas fa-map-marker-alt me-2"></i>Get Coordinates from Map URL
                    </button>
                    <small class="form-text text-muted ms-2">Click this button if you've entered a Google Maps URL above</small>
                </div>

                <!-- Coordinates Section -->
                <div class="mb-10 col-md-12">
                    <h4 class="mb-4">Location Coordinates</h4>
                    <p class="text-muted mb-4">Add precise coordinates for better location-based sorting and distance calculation.</p>
                </div>
                
                <div class="mb-10 col-md-4">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="number" id="latitude" value="{{ old('latitude') }}" name="latitude" class="form-control form-control-solid" step="any" placeholder="e.g., 30.0444"/>
                    <small class="form-text text-muted">Enter the latitude coordinate (e.g., 30.0444 for Cairo)</small>
                </div>
                
                <div class="mb-10 col-md-4">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="number" id="longitude" value="{{ old('longitude') }}" name="longitude" class="form-control form-control-solid" step="any" placeholder="e.g., 31.2357"/>
                    <small class="form-text text-muted">Enter the longitude coordinate (e.g., 31.2357 for Cairo)</small>
                </div>
                
                <div class="mb-10 col-md-4">
                    <label for="city" class="form-label">City</label>
                    <input type="text" id="city" value="{{ old('city') }}" name="city" class="form-control form-control-solid" placeholder="e.g., Cairo"/>
                    <small class="form-text text-muted">Enter the city name for better location matching</small>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="region" class="form-label">Region/Governorate</label>
                    <input type="text" id="region" value="{{ old('region') }}" name="region" class="form-control form-control-solid" placeholder="e.g., Cairo Governorate"/>
                    <small class="form-text text-muted">Enter the region or governorate name</small>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" id="country" value="{{ old('country', 'Egypt') }}" name="country" class="form-control form-control-solid" placeholder="e.g., Egypt"/>
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

                <div class="mb-10 col-md-6">
                    <label class="required form-label">Phone Numbers</label>
                    <div id="phone-repeater" data-kt-repeater="list">
                        <div data-repeater-list="phones">
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
                        </div>
                        <div class="form-group">
                            <button type="button" data-repeater-create class="btn btn-light-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                Add Phone
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Opening Hours Section -->
                <div class="mb-10 col-md-12">
                    <h4 class="mb-4">Opening Hours</h4>
                    <p class="text-muted mb-4">Set opening hours by selecting days and their corresponding times. You can create multiple time slots for different days.</p>
                </div>

                <div class="mb-10 col-md-12">
                    <div id="opening-hours-repeater" data-kt-repeater="list">
                        <div data-repeater-list="opening_hours">
                            <div data-repeater-item class="form-group row mb-5">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Select Days</label>
                                                    <div class="form-check-group">
                                                        @php
                                                            $days = [
                                                                'monday' => 'Monday',
                                                                'tuesday' => 'Tuesday', 
                                                                'wednesday' => 'Wednesday',
                                                                'thursday' => 'Thursday',
                                                                'friday' => 'Friday',
                                                                'saturday' => 'Saturday',
                                                                'sunday' => 'Sunday'
                                                            ];
                                                        @endphp
                                                        @foreach($days as $dayKey => $dayName)
                                                            <div class="form-check form-check-custom form-check-solid">
                                                                <input class="form-check-input day-checkbox" type="checkbox" 
                                                                       name="opening_hours[0][days][]" 
                                                                       value="{{ $dayKey }}" 
                                                                       id="day_{{ $dayKey }}_0">
                                                                <label class="form-check-label" for="day_{{ $dayKey }}_0">
                                                                    {{ $dayName }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input closed-toggle" type="checkbox" 
                                                               name="opening_hours[0][is_closed]" 
                                                               value="1" 
                                                               id="closed_0">
                                                        <label class="form-check-label" for="closed_0">
                                                            <strong>Closed</strong>
                                                        </label>
                                                    </div>
                                                    
                                                    <div class="time-inputs">
                                                        <div class="mb-3">
                                                            <label class="form-label">Opening Time</label>
                                                            <input type="time" 
                                                                   class="form-control form-control-solid" 
                                                                   name="opening_hours[0][opening_time]"
                                                                   id="opening_0">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Closing Time</label>
                                                            <input type="time" 
                                                                   class="form-control form-control-solid" 
                                                                   name="opening_hours[0][closing_time]"
                                                                   id="closing_0">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                        <i class="ki-duotone ki-trash fs-5">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i>
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" data-repeater-create class="btn btn-light-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                Add Time Slot
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="mb-10 col-md-12">
                    <h4 class="mb-4">Branch Gallery</h4>
                    <p class="text-muted mb-4">Add images for the branch. The main image will be used as the primary display image, and additional images will be added to the gallery.</p>
                </div>

                <!-- Main Image Section -->
                <div class="mb-10 col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="main_image" class="form-label">Main Branch Image</label>
                            <input type="file" id="main_image" name="main_image" class="form-control form-control-solid" accept="image/*"/>
                            <small class="form-text text-muted">This will be the primary image displayed for the branch</small>
                            
                            <!-- Image Preview -->
                            <div id="main_image_preview" class="mt-3" style="display: none;">
                                <label class="form-label">Preview:</label>
                                <div class="mb-3">
                                    <img id="main_image_preview_img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="gallery_images" class="form-label">Gallery Images</label>
                            <input type="file" id="gallery_images" name="gallery_images[]" class="form-control form-control-solid" accept="image/*" multiple/>
                            <small class="form-text text-muted">Select multiple images to add to the branch gallery</small>
                            
                            <!-- Gallery Images Preview -->
                            <div id="gallery_images_preview" class="mt-3" style="display: none;">
                                <label class="form-label">Preview:</label>
                                <div id="gallery_preview_container" class="row">
                                    <!-- Preview images will be added here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
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

        $(document).ready(function() {
            initCoordinateExtraction();
            initImagePreview();
        });

        // Image preview functionality
        function initImagePreview() {
            // Main image preview
            $('#main_image').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#main_image_preview_img').attr('src', e.target.result);
                        $('#main_image_preview').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#main_image_preview').hide();
                }
            });

            // Gallery images preview
            $('#gallery_images').on('change', function(e) {
                const files = e.target.files;
                const previewContainer = $('#gallery_preview_container');
                const previewDiv = $('#gallery_images_preview');
                
                // Clear previous previews
                previewContainer.empty();
                
                if (files.length > 0) {
                    previewDiv.show();
                    
                    Array.from(files).forEach(function(file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewHtml = `
                                <div class="col-md-3 mb-3">
                                    <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                </div>
                            `;
                            previewContainer.append(previewHtml);
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    previewDiv.hide();
                }
            });
        }

        function initOpeningHours() {
            // Handle closed toggle
            $(document).on('change', '.closed-toggle', function() {
                const timeInputs = $(this).closest('.card-body').find('.time-inputs');
                const isClosed = $(this).is(':checked');
                
                if (isClosed) {
                    timeInputs.find('input[type="time"]').prop('disabled', true).val('');
                    $(this).val('1');
                } else {
                    timeInputs.find('input[type="time"]').prop('disabled', false);
                    $(this).val('');
                }
            });
        }

        $(document).ready(function() {
            // Initialize opening hours repeater
            $('#opening-hours-repeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                    fixRepeaterIds();
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
            
            initOpeningHours();
            initDayConflictDetection();
        });

        function fixRepeaterIds() {
            $('#opening-hours-repeater [data-repeater-item]').each(function(index) {
                const $item = $(this);
                
                // Fix day checkboxes
                $item.find('.day-checkbox').each(function() {
                    const $checkbox = $(this);
                    const dayValue = $checkbox.val();
                    const newId = `day_${dayValue}_${index}`;
                    $checkbox.attr('id', newId);
                    $checkbox.attr('name', `opening_hours[${index}][days][]`);
                    $checkbox.next('label').attr('for', newId);
                });
                
                // Fix closed toggle
                const $closedToggle = $item.find('.closed-toggle');
                $closedToggle.attr('id', `closed_${index}`);
                $closedToggle.attr('name', `opening_hours[${index}][is_closed]`);
                $closedToggle.next('label').attr('for', `closed_${index}`);
                
                // Fix time inputs
                $item.find('input[name*="opening_time"]').attr('name', `opening_hours[${index}][opening_time]`);
                $item.find('input[name*="closing_time"]').attr('name', `opening_hours[${index}][closing_time]`);
            });
            
            updateDayConflicts();
        }

        function updateDayConflicts() {
            const selectedDays = new Set();
            
            // Collect all selected days from existing time slots
            $('#opening-hours-repeater [data-repeater-item]').each(function() {
                const $item = $(this);
                $item.find('.day-checkbox:checked').each(function() {
                    selectedDays.add($(this).val());
                });
            });
            
            // Disable already selected days in all time slots
            $('#opening-hours-repeater [data-repeater-item]').each(function() {
                const $item = $(this);
                $item.find('.day-checkbox').each(function() {
                    const $checkbox = $(this);
                    const dayValue = $checkbox.val();
                    const isChecked = $checkbox.is(':checked');
                    
                    if (selectedDays.has(dayValue) && !isChecked) {
                        $checkbox.prop('disabled', true);
                        $checkbox.next('label').addClass('text-muted');
                    } else {
                        $checkbox.prop('disabled', false);
                        $checkbox.next('label').removeClass('text-muted');
                    }
                });
            });
        }

        function initDayConflictDetection() {
            // Handle day checkbox changes
            $(document).on('change', '.day-checkbox', function() {
                updateDayConflicts();
            });
        }
    </script>
@endsection

