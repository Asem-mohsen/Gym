@extends('layout.admin.master')

@section('title' , 'Edit ' . $branch->name . ' Branch')

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
                                            <button type="button" data-repeater-delete class="btn btn-md btn-light-danger">
                                                Delete
                                            </button>
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

                <!-- Opening Hours Section -->
                <div class="mb-10 col-md-12">
                    <h4 class="mb-4">Opening Hours</h4>
                    <p class="text-muted mb-4">Set opening hours by selecting days and their corresponding times. You can create multiple time slots for different days.</p>
                </div>

                <div class="mb-10 col-md-12">
                    <div id="opening-hours-repeater" data-kt-repeater="list">
                        <div data-repeater-list="opening_hours">
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
                                
                                // Group existing hours by time slots
                                $groupedHours = [];
                                foreach($branch->openingHours as $hours) {
                                    $key = ($hours->opening_time ? $hours->opening_time->format('H:i') : '') . '_' . 
                                           ($hours->closing_time ? $hours->closing_time->format('H:i') : '') . '_' . 
                                           ($hours->is_closed ? '1' : '0');
                                    if (!isset($groupedHours[$key])) {
                                        $groupedHours[$key] = [
                                            'days' => [],
                                            'opening_time' => $hours->opening_time ? $hours->opening_time->format('H:i') : '',
                                            'closing_time' => $hours->closing_time ? $hours->closing_time->format('H:i') : '',
                                            'is_closed' => $hours->is_closed
                                        ];
                                    }
                                    $groupedHours[$key]['days'][] = $hours->day_of_week;
                                }
                            @endphp
                            
                            @if(count($groupedHours) > 0)
                                @foreach($groupedHours as $index => $timeSlot)
                                    <div data-repeater-item class="form-group row mb-5">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Select Days</label>
                                                            <div class="form-check-group d-flex flex-column gap-2">
                                                                @foreach($days as $dayKey => $dayName)
                                                                    <div class="form-check form-check-custom form-check-solid">
                                                                        <input class="form-check-input day-checkbox" type="checkbox" 
                                                                               name="opening_hours[{{ $index }}][days][]" 
                                                                               value="{{ $dayKey }}" 
                                                                               id="day_{{ $dayKey }}_{{ $index }}"
                                                                               {{ in_array($dayKey, $timeSlot['days']) ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="day_{{ $dayKey }}_{{ $index }}">
                                                                            {{ $dayName }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-check form-switch mb-3">
                                                                <input class="form-check-input closed-toggle" type="checkbox" 
                                                                       name="opening_hours[{{ $index }}][is_closed]" 
                                                                       value="1" 
                                                                       id="closed_{{ $index }}"
                                                                       {{ $timeSlot['is_closed'] ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="closed_{{ $index }}">
                                                                    <strong>Closed</strong>
                                                                </label>
                                                            </div>
                                                            
                                                            <div class="time-inputs">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Opening Time</label>
                                                                    <input type="time" 
                                                                           class="form-control form-control-solid" 
                                                                           name="opening_hours[{{ $index }}][opening_time]"
                                                                           id="opening_{{ $index }}"
                                                                           value="{{ $timeSlot['opening_time'] }}"
                                                                           {{ $timeSlot['is_closed'] ? 'disabled' : '' }}>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Closing Time</label>
                                                                    <input type="time" 
                                                                           class="form-control form-control-solid" 
                                                                           name="opening_hours[{{ $index }}][closing_time]"
                                                                           id="closing_{{ $index }}"
                                                                           value="{{ $timeSlot['closing_time'] }}"
                                                                           {{ $timeSlot['is_closed'] ? 'disabled' : '' }}>
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
                                @endforeach
                            @else
                                <div data-repeater-item class="form-group row mb-5">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Select Days</label>
                                                        <div class="form-check-group d-flex flex-column gap-2">
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
                            @endif
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
                        <!-- Update Main Image -->
                        <div class="col-md-8">
                            <label for="main_image" class="form-label">Update Main Branch Image</label>
                            <input type="file" id="main_image" name="main_image" class="form-control form-control-solid" accept="image/*"/>
                            <small class="form-text text-muted">Leave empty to keep current image</small>
                            
                            <!-- Image Preview -->
                            <div id="main_image_preview" class="mt-3" style="display: none;">
                                <label class="form-label">Preview:</label>
                                <div class="mb-3">
                                    <img id="main_image_preview_img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <!-- Current Main Image -->
                        @if($branch->getFirstMediaUrl('branch_images'))
                            <div class="col-md-4">
                                <label class="form-label">Current Main Image</label>
                                <div class="mb-3">
                                    <img src="{{ $branch->getFirstMediaUrl('branch_images') }}" alt="Current main image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-10 col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="gallery_images" class="form-label">Add More Gallery Images</label>
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

                        <!-- Current Gallery Images -->
                        @if($branch->galleries->count() > 0)
                            <div class="col-md-4">
                                <label class="form-label">Current Gallery Images</label>
                                <div class="row">
                                    @foreach($branch->galleries as $gallery)
                                        @foreach($gallery->media as $media)
                                            <div class="col-md-6 mb-3">
                                                <img src="{{ $media->getUrl() }}" alt="Gallery image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endif
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
            initImagePreview();
        });
    </script>
@endsection