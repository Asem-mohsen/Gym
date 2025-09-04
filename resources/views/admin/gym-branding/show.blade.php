@extends('layout.admin.master')

@section('title', 'Gym Branding Settings')

@section('page-title', 'Gym Branding Settings')

@section('main-breadcrumb', 'Site Settings')
@section('main-breadcrumb-link', route('site-settings.edit'))

@section('sub-breadcrumb', 'Branding')

@section('content')
<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="card-title">Gym Branding & Customization</h3>
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-secondary" id="resetBranding">
                    <i class="ki-duotone ki-refresh fs-2"></i>Reset to Defaults
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" id="brandingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="colors-tab" data-bs-toggle="tab" data-bs-target="#colors" type="button" role="tab">
                        <i class="ki-duotone ki-palette fs-2 me-2"></i>Colors & Typography
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" type="button" role="tab">
                        <i class="ki-duotone ki-layout-grid fs-2 me-2"></i>Page Sections
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">
                        <i class="ki-duotone ki-image fs-2 me-2"></i>Media & Banners
                    </button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="brandingTabsContent">
                <!-- Colors Tab -->
                <div class="tab-pane fade show active" id="colors" role="tabpanel">
                    <form id="colorsForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="mb-4">Color Scheme</h4>
                                
                                <div class="row g-4">
                                    <!-- Primary Color -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="primary_color" class="form-label fw-bold">Primary Color</label>
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="color" class="form-control form-control-color w-100px h-50px" 
                                                       id="primary_color" name="primary_color" 
                                                       value="{{ $brandingData['primary_color'] ?? '#0d6efd' }}">
                                                <div class="flex-grow-1">
                                                    <input type="hidden" class="form-control" 
                                                           value="{{ $brandingData['primary_color'] ?? '#0d6efd' }}" 
                                                           id="primary_color_text" readonly>
                                                    <small class="form-text text-muted">Main brand color for buttons, links, and highlights</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Secondary Color -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secondary_color" class="form-label fw-bold">Secondary Color</label>
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="color" class="form-control form-control-color w-100px h-50px" 
                                                       id="secondary_color" name="secondary_color" 
                                                       value="{{ $brandingData['secondary_color'] ?? '#6c757d' }}">
                                                <div class="flex-grow-1">
                                                    <input type="hidden" class="form-control" 
                                                           value="{{ $brandingData['secondary_color'] ?? '#6c757d' }}" 
                                                           id="secondary_color_text" readonly>
                                                    <small class="form-text text-muted">Supporting color for secondary elements</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Accent Color -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="accent_color" class="form-label fw-bold">Accent Color</label>
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="color" class="form-control form-control-color w-100px h-50px" 
                                                       id="accent_color" name="accent_color" 
                                                       value="{{ $brandingData['accent_color'] ?? '#fd7e14' }}">
                                                <div class="flex-grow-1">
                                                    <input type="hidden" class="form-control" 
                                                           value="{{ $brandingData['accent_color'] ?? '#fd7e14' }}" 
                                                           id="accent_color_text" readonly>
                                                    <small class="form-text text-muted">Highlight color for special elements and CTAs</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Text Color -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="text_color" class="form-label fw-bold">Text Color</label>
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="color" class="form-control form-control-color w-100px h-50px" 
                                                       id="text_color" name="text_color" 
                                                       value="{{ $brandingData['text_color'] ?? '#212529' }}">
                                                <div class="flex-grow-1">
                                                    <input type="hidden" class="form-control" 
                                                           value="{{ $brandingData['text_color'] ?? '#212529' }}" 
                                                           id="text_color_text" readonly>
                                                    <small class="form-text text-muted">Main text color for content</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mt-5 mb-4">Typography & Styling</h4>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="font_family" class="form-label fw-bold">Font Family</label>
                                            <select class="form-control" id="font_family" name="font_family">
                                                <option value="Inter, system-ui, -apple-system, sans-serif" 
                                                        {{ ($brandingData['font_family'] ?? '') == 'Inter, system-ui, -apple-system, sans-serif' ? 'selected' : '' }}>
                                                    Inter (Default)
                                                </option>
                                                <option value="'Roboto', sans-serif" 
                                                        {{ ($brandingData['font_family'] ?? '') == "'Roboto', sans-serif" ? 'selected' : '' }}>
                                                    Roboto
                                                </option>
                                                <option value="'Open Sans', sans-serif" 
                                                        {{ ($brandingData['font_family'] ?? '') == "'Open Sans', sans-serif" ? 'selected' : '' }}>
                                                    Open Sans
                                                </option>
                                                <option value="'Poppins', sans-serif" 
                                                        {{ ($brandingData['font_family'] ?? '') == "'Poppins', sans-serif" ? 'selected' : '' }}>
                                                    Poppins
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="border_radius" class="form-label fw-bold">Border Radius</label>
                                            <select class="form-control" id="border_radius" name="border_radius">
                                                <option value="0" {{ ($brandingData['border_radius'] ?? '') == '0' ? 'selected' : '' }}>None</option>
                                                <option value="0.25rem" {{ ($brandingData['border_radius'] ?? '') == '0.25rem' ? 'selected' : '' }}>Small</option>
                                                <option value="0.375rem" {{ ($brandingData['border_radius'] ?? '') == '0.375rem' ? 'selected' : '' }}>Medium (Default)</option>
                                                <option value="0.5rem" {{ ($brandingData['border_radius'] ?? '') == '0.5rem' ? 'selected' : '' }}>Large</option>
                                                <option value="1rem" {{ ($brandingData['border_radius'] ?? '') == '1rem' ? 'selected' : '' }}>Extra Large</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Live Preview -->
                            <div class="col-md-4">
                                <h4 class="mb-4">Live Preview</h4>
                                <div id="colorsPreviewArea" class="border rounded p-4" style="min-height: 400px;">
                                    <h5 class="mb-3">Before & After</h5>
                                    
                                    <div class="mb-4">
                                        <h6 class="text-muted mb-2">Current Appearance</h6>
                                        <div class="btn-group mb-2">
                                            <button class="btn btn-primary">Primary</button>
                                            <button class="btn btn-secondary">Secondary</button>
                                            <button class="btn btn-outline-primary">Outline</button>
                                        </div>
                                        <p class="text-muted small">This shows your current branding</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h6 class="text-muted mb-2">New Appearance</h6>
                                        <div id="newButtons" class="btn-group mb-2">
                                            <button class="btn btn-primary">Primary</button>
                                            <button class="btn btn-secondary">Secondary</button>
                                            <button class="btn btn-outline-primary">Outline</button>
                                        </div>
                                        <p class="text-muted small">This shows how it will look with new colors</p>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <strong>Info:</strong> This is how alerts will appear
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Sample Card</h6>
                                            <p class="card-text">This demonstrates your border radius and styling.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i>Save Colors & Typography
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Page Sections Tab -->
                <div class="tab-pane fade" id="sections" role="tabpanel">
                    <form id="sectionsForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="mb-4">Home Page Sections</h4>
                                <p class="text-muted mb-4">Choose which sections to display on your home page and customize their appearance.</p>
                                
                                <div class="row g-4">
                                    @php
                                        $currentSections = $brandingData['home_page_sections'] ?? $defaultSections;
                                    @endphp
                                    
                                    @foreach($defaultSections as $section => $enabled)
                                        <div class="col-md-6">
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="section_{{ $section }}" 
                                                    name="home_page_sections[{{ $section }}]" 
                                                    value="1" 
                                                    {{ ($currentSections[$section] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="section_{{ $section }}">
                                                    <span class="fw-bold">{{ ucfirst($section) }} Section</span>
                                                    <div class="text-muted small">
                                                        @switch($section)
                                                            @case('hero')
                                                                Welcome banner and main call-to-action
                                                                @break
                                                            @case('choseus')
                                                                Why choose us section
                                                                @break
                                                            @case('classes')
                                                                Available classes and schedules
                                                                @break
                                                            @case('banner')
                                                                Promotional banner section
                                                                @break
                                                            @case('memberships')
                                                                Membership plans and pricing
                                                                @break
                                                            @case('gallery')
                                                                Photo gallery and testimonials
                                                                @break
                                                            @case('team')
                                                                Our team and trainers
                                                                @break
                                                        @endswitch
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Section Preview -->
                            <div class="col-md-4">
                                <h4 class="mb-4">Home Page Preview</h4>
                                <div id="sectionsPreviewArea" class="border rounded p-4" style="min-height: 400px;">
                                    <div class="text-center mb-3">
                                        <i class="ki-duotone ki-layout-grid fs-2x text-muted"></i>
                                    </div>
                                    <h6 class="text-center mb-3">Home Page Layout</h6>
                                    <div id="sectionsPreview" class="d-flex flex-column gap-2">
                                        <!-- Dynamic sections will be shown here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i>Save Page Sections
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Media Tab -->
                <div class="tab-pane fade" id="media" role="tabpanel">
                    <form id="mediaForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="mb-4">Media & Banners</h4>
                                <p class="text-muted mb-4">Upload custom images for different sections of your website.</p>
                                
                                <div class="row g-4">
                                    @php
                                        $currentMedia = $brandingData['media_settings'] ?? [];
                                        $multipleFileTypes = \App\Models\GymSetting::getMultipleFileMediaTypes();
                                    @endphp
                                    
                                    @foreach($mediaTypes as $mediaType)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="media_{{ $mediaType }}" class="form-label fw-bold">
                                                {{ ucwords(str_replace('_', ' ', $mediaType)) }}
                                                @if(isset($multipleFileTypes[$mediaType]) && $multipleFileTypes[$mediaType] > 1)
                                                    <span class="badge badge-info ms-2">{{ $multipleFileTypes[$mediaType] }} images</span>
                                                @endif
                                            </label>
                                            
                                            @if(isset($multipleFileTypes[$mediaType]) && $multipleFileTypes[$mediaType] > 1)
                                                <!-- Multiple file inputs -->
                                                <div class="mb-3">
                                                    @for($i = 0; $i < $multipleFileTypes[$mediaType]; $i++)
                                                        <div class="d-flex align-items-center gap-3 mb-2">
                                                            <div class="w-80px h-50px border rounded d-flex align-items-center justify-content-center bg-light overflow-hidden">
                                                                @if(isset($brandingData['media_urls'][$mediaType]) && is_array($brandingData['media_urls'][$mediaType]) && isset($brandingData['media_urls'][$mediaType][$i]))
                                                                    <img src="{{ $brandingData['media_urls'][$mediaType][$i] }}" 
                                                                         alt="{{ $mediaType }} {{ $i + 1 }}" 
                                                                         class="w-100 h-100 object-fit-cover">
                                                                @else
                                                                    <i class="fa fa-image fs-1 text-muted"></i>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <input type="file" class="form-control form-control-sm" 
                                                                       id="media_{{ $mediaType }}_{{ $i }}" 
                                                                       name="media_settings[{{ $mediaType }}][{{ $i }}]" 
                                                                       accept="image/*">
                                                                <small class="form-text text-muted">
                                                                    Image {{ $i + 1 }} @if($i == 0)(Main)@endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            @else
                                                <!-- Single file input -->
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="w-100px h-60px border rounded d-flex align-items-center justify-content-center bg-light overflow-hidden">
                                                        @if(isset($brandingData['media_urls'][$mediaType]) && $brandingData['media_urls'][$mediaType])
                                                            <img src="{{ $brandingData['media_urls'][$mediaType] }}" 
                                                                 alt="{{ $mediaType }}" 
                                                                 class="w-100 h-100 object-fit-cover">
                                                        @else
                                                            <i class="fa fa-image fs-2x text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <input type="file" class="form-control" 
                                                               id="media_{{ $mediaType }}" 
                                                               name="media_settings[{{ $mediaType }}]" 
                                                               accept="image/*">
                                                        <small class="form-text text-muted">
                                                            Recommended: 1920x600px for banners
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Media Preview -->
                            <div class="col-md-4">
                                <h4 class="mb-4">Media Preview</h4>
                                <div id="mediaPreviewArea" class="border rounded p-4" style="min-height: 400px;">
                                    <div class="text-center mb-3">
                                        <i class="ki-duotone ki-image fs-2x text-muted"></i>
                                    </div>
                                    <h6 class="text-center mb-3">Uploaded Images</h6>
                                    <div id="mediaPreview" class="d-flex flex-column gap-2">
                                        <!-- Dynamic media preview will be shown here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i>Save Media Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color picker functionality
    const colorInputs = ['primary_color', 'secondary_color', 'accent_color', 'text_color'];
    
    colorInputs.forEach(colorName => {
        const colorInput = document.getElementById(colorName);
        const textInput = document.getElementById(colorName + '_text');
        
        if (colorInput && textInput) {
            colorInput.addEventListener('change', function() {
                textInput.value = this.value;
                updateColorsPreview();
            });
            
            textInput.addEventListener('input', function() {
                colorInput.value = this.value;
                updateColorsPreview();
            });
        }
    });
    
    function updateColorsPreview() {
        const primaryColor = document.getElementById('primary_color').value;
        const secondaryColor = document.getElementById('secondary_color').value;
        const accentColor = document.getElementById('accent_color').value;
        const textColor = document.getElementById('text_color').value;
        
        // Update new buttons with custom styles
        const newButtons = document.getElementById('newButtons');
        if (newButtons) {
            const buttons = newButtons.querySelectorAll('.btn');
            buttons.forEach(btn => {
                if (btn.classList.contains('btn-primary')) {
                    btn.style.backgroundColor = primaryColor;
                    btn.style.borderColor = primaryColor;
                } else if (btn.classList.contains('btn-secondary')) {
                    btn.style.backgroundColor = secondaryColor;
                    btn.style.borderColor = secondaryColor;
                } else if (btn.classList.contains('btn-outline-primary')) {
                    btn.style.color = primaryColor;
                    btn.style.borderColor = primaryColor;
                }
            });
        }
        
        // Update preview area with custom CSS variables
        const previewArea = document.getElementById('colorsPreviewArea');
        if (previewArea) {
            previewArea.style.setProperty('--color-primary', primaryColor);
            previewArea.style.setProperty('--color-secondary', secondaryColor);
            previewArea.style.setProperty('--color-accent', accentColor);
            previewArea.style.setProperty('--color-text', textColor);
        }
    }
    
    // Sections functionality
    const sectionCheckboxes = document.querySelectorAll('input[name^="home_page_sections"]');
    const sectionsPreview = document.getElementById('sectionsPreview');
    
    function updateSectionsPreview() {
        if (!sectionsPreview) return;
        
        sectionsPreview.innerHTML = '';
        sectionCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const sectionName = checkbox.id.replace('section_', '');
                const sectionDiv = document.createElement('div');
                sectionDiv.className = 'd-flex align-items-center gap-2 p-2 bg-light rounded';
                sectionDiv.innerHTML = `
                    <i class="ki-duotone ki-check-circle fs-2 text-success"></i>
                    <span class="small">${sectionName.charAt(0).toUpperCase() + sectionName.slice(1)}</span>
                `;
                sectionsPreview.appendChild(sectionDiv);
            }
        });
    }
    
    sectionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSectionsPreview);
    });
    
    // Media functionality
    const mediaInputs = document.querySelectorAll('input[type="file"]');
    const mediaPreview = document.getElementById('mediaPreview');
    
    function updateMediaPreview() {
        if (!mediaPreview) return;
        
        mediaPreview.innerHTML = '';
        
        // Show existing media
        @if(isset($brandingData['media_urls']))
            @foreach($brandingData['media_urls'] as $mediaType => $mediaUrls)
                @if($mediaUrls)
                    @if(is_array($mediaUrls))
                        @foreach($mediaUrls as $index => $mediaUrl)
                            const existingMediaDiv{{ $mediaType }}{{ $index }} = document.createElement('div');
                            existingMediaDiv{{ $mediaType }}{{ $index }}.className = 'd-flex align-items-center gap-2 p-2 bg-light rounded';
                            existingMediaDiv{{ $mediaType }}{{ $index }}.innerHTML = `
                                <img src="{{ $mediaUrl }}" alt="{{ $mediaType }} {{ $index + 1 }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <span class="small">{{ str_replace('_', ' ', $mediaType) }} {{ $index + 1 }}</span>
                            `;
                            mediaPreview.appendChild(existingMediaDiv{{ $mediaType }}{{ $index }});
                        @endforeach
                    @else
                        const existingMediaDiv{{ $mediaType }} = document.createElement('div');
                        existingMediaDiv{{ $mediaType }}.className = 'd-flex align-items-center gap-2 p-2 bg-light rounded';
                        existingMediaDiv{{ $mediaType }}.innerHTML = `
                            <img src="{{ $mediaUrls }}" alt="{{ $mediaType }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                            <span class="small">{{ str_replace('_', ' ', $mediaType) }}</span>
                        `;
                        mediaPreview.appendChild(existingMediaDiv{{ $mediaType }});
                    @endif
                @endif
            @endforeach
        @endif
        
        // Show newly selected files
        mediaInputs.forEach(input => {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                const mediaType = input.id.replace(/^media_/, '').replace(/_\d+$/, '');
                const imageNumber = input.id.match(/_(\d+)$/);
                const imageNum = imageNumber ? imageNumber[1] : '';
                
                reader.onload = function(e) {
                    const mediaDiv = document.createElement('div');
                    mediaDiv.className = 'd-flex align-items-center gap-2 p-2 bg-light rounded';
                    mediaDiv.innerHTML = `
                        <img src="${e.target.result}" alt="${mediaType}${imageNum ? ' ' + (parseInt(imageNum) + 1) : ''}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                        <span class="small">${mediaType.replace(/_/g, ' ')}${imageNum ? ' ' + (parseInt(imageNum) + 1) : ''} (New)</span>
                    `;
                    mediaPreview.appendChild(mediaDiv);
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    mediaInputs.forEach(input => {
        input.addEventListener('change', updateMediaPreview);
    });
    
    // Initial updates
    updateColorsPreview();
    updateSectionsPreview();
    updateMediaPreview();
    
    // Form submissions
    const colorsForm = document.getElementById('colorsForm');
    const sectionsForm = document.getElementById('sectionsForm');
    const mediaForm = document.getElementById('mediaForm');
    
    colorsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        submitForm(this, 'colors_typography');
        return false;
    });
    
    sectionsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        submitForm(this, 'page_sections');
        return false;
    });
    
    mediaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        submitForm(this, 'media_settings');
        return false;
    });
    
    function submitForm(form, formType) {
        const formData = new FormData(form);
        
        // Add the form type to identify which section is being updated
        formData.append('form_type', formType);
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Log the request details
        console.log('Submitting form:', {
            formType: formType,
            url: `{{ route('gym-branding.update', $siteSetting->id) }}`,
            csrfToken: csrfToken,
            formData: Object.fromEntries(formData)
        });
        
        fetch(`{{ route('gym-branding.update', $siteSetting->id) }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 302) {
                    throw new Error('Redirect detected - possible authentication issue');
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Get the display name for the form type
                const displayNames = {
                    'colors_typography': 'Colors & Typography',
                    'page_sections': 'Page Sections',
                    'media_settings': 'Media Settings'
                };
                const displayName = displayNames[formType] || formType;
                toastr.success(`${displayName} updated successfully!`);
                setTimeout(function() {
                        location.reload();
                }, 3000);
            } else {
                toastr.error('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while saving the settings: ' + error.message);
        });
    }
    
    // Reset functionality
    const resetBtn = document.getElementById('resetBranding');
    resetBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to reset all branding settings to defaults?')) {
            fetch(`{{ route('gym-branding.reset', $siteSetting->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Branding settings reset to defaults!');
                    location.reload();
                } else {
                    toastr.error('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while resetting the settings.');
            });
        }
    });
});
</script>
@endsection


