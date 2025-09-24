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
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="texts-tab" data-bs-toggle="tab" data-bs-target="#texts" type="button" role="tab">
                        <i class="ki-duotone ki-document fs-2 me-2"></i>Page Texts
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="repeaters-tab" data-bs-toggle="tab" data-bs-target="#repeaters" type="button" role="tab">
                        <i class="ki-duotone ki-repeat fs-2 me-2"></i>Repeater Fields
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
                            </div>
                            
                            <!-- Live Preview -->
                            <div class="col-md-4">
                                <h4 class="mb-4 text-center">Live Preview</h4>
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

                <!-- Page Texts Tab -->
                <div class="tab-pane fade" id="texts" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="mb-4">Select Page</h4>
                            <p class="text-muted mb-4">Choose a page to customize its text content.</p>
                            
                            <div class="list-group" id="pageList">
                                @foreach($pageTypes as $pageKey => $pageName)
                                    <button type="button" class="list-group-item list-group-item-action page-selector" 
                                            data-page="{{ $pageKey }}">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-document fs-2 me-3"></i>
                                            <div>
                                                <h6 class="mb-1">{{ $pageName }}</h6>
                                                <small class="text-muted">Customize text content</small>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div id="textCustomizationArea" class="d-none">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 id="selectedPageTitle">Select a page to customize</h4>
                                    <div>
                                        <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="ki-duotone ki-exit-up fs-2"></i>Go to Website
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-warning" id="resetPageTexts">
                                            <i class="ki-duotone ki-refresh fs-2"></i>Reset to Defaults
                                        </button>
                                    </div>
                                </div>
                                
                                <form id="textsForm">
                                    @csrf
                                    <input type="hidden" id="currentPageType" name="page_type">
                                    <input type="hidden" name="form_type" value="page_texts">
                                    
                                    <div id="textFieldsContainer">
                                        <!-- Dynamic text fields will be loaded here -->
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ki-duotone ki-check fs-2"></i>Save Page Texts
                                            </button>
                                            <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}" target="_blank" class="btn btn-outline-secondary ms-2">
                                                <i class="ki-duotone ki-exit-up fs-2"></i>View on Website
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Repeater Fields Tab -->
                <div class="tab-pane fade" id="repeaters" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="mb-4">Select Repeater Section</h4>
                            <p class="text-muted mb-4">Choose a repeater section to customize its content.</p>
                            
                            <div class="list-group" id="repeaterList">
                                @foreach($repeaterConfigs as $sectionKey => $config)
                                    <button type="button" class="list-group-item list-group-item-action repeater-selector" 
                                            data-section="{{ $sectionKey }}">
                                        <div class="d-flex align-items-start">
                                            <i class="ki-duotone ki-repeat fs-2 me-3 mt-1"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $config['title'] }}</h6>
                                                <p class="mb-1 text-muted small">{{ $config['description'] }}</p>
                                                <div class="d-flex flex-column">
                                                    <small class="text-primary fw-bold">{{ $config['page_location'] }}</small>
                                                    <small class="text-muted">{{ $config['section_location'] }}</small>
                                                    <small class="text-info">Max {{ $config['max_items'] }} items</small>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div id="repeaterCustomizationArea" class="d-none">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 id="selectedRepeaterTitle">Select a repeater section to customize</h4>
                                    <div>
                                        <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="ki-duotone ki-exit-up fs-2"></i>Go to Website
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-warning" id="resetRepeaters">
                                            <i class="ki-duotone ki-refresh fs-2"></i>Reset to Defaults
                                        </button>
                                    </div>
                                </div>
                                
                                <form id="repeatersForm">
                                    @csrf
                                    <input type="hidden" id="currentRepeaterSection" name="section">
                                    <input type="hidden" name="form_type" value="repeater_fields">
                                    
                                    <div id="repeaterFieldsContainer">
                                        <!-- Dynamic repeater fields will be loaded here -->
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ki-duotone ki-check fs-2"></i>Save Repeater Fields
                                            </button>
                                            <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}" target="_blank" class="btn btn-outline-secondary ms-2">
                                                <i class="ki-duotone ki-exit-up fs-2"></i>View on Website
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
.page-selector.active h6,
.page-selector.active small {
    color: white !important;
}

.repeater-selector.active h6,
.repeater-selector.active small {
    color: white !important;
}

.repeater-selector.active p {
    color: white !important;
}

.repeater-item {
    border: 1px solid #e4e6ea;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f8f9fa;
}

.repeater-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.repeater-item-title {
    font-weight: 600;
    color: #181c32;
}

.repeater-item-actions {
    display: flex;
    gap: 10px;
}

.repeater-field-group {
    margin-bottom: 15px;
}

.repeater-field-group label {
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
}

.repeater-field-group .form-control,
.repeater-field-group .form-select {
    border: 1px solid #e4e6ea;
    border-radius: 6px;
}

.repeater-field-group .form-control:focus,
.repeater-field-group .form-select:focus {
    border-color: #009ef7;
    box-shadow: 0 0 0 0.2rem rgba(0, 158, 247, 0.25);
}

.add-repeater-item {
    border: 2px dashed #e4e6ea;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-repeater-item:hover {
    border-color: #009ef7;
    background: #f1f8ff;
}

.add-repeater-item i {
    font-size: 2rem;
    color: #009ef7;
    margin-bottom: 10px;
}

/* Button hover effects */
.btn-outline-danger:hover {
    color: white !important;
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-primary:hover {
    color: white !important;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-outline-secondary:hover {
    color: white !important;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-warning:hover {
    color: white !important;
    background-color: #ffc107;
    border-color: #ffc107;
}

</style>
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
                    'media_settings': 'Media Settings',
                    'page_texts': 'Page Texts'
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
    
    // Text customization functionality
    const pageSelectors = document.querySelectorAll('.page-selector');
    const textCustomizationArea = document.getElementById('textCustomizationArea');
    const selectedPageTitle = document.getElementById('selectedPageTitle');
    const currentPageType = document.getElementById('currentPageType');
    const textFieldsContainer = document.getElementById('textFieldsContainer');
    const textsForm = document.getElementById('textsForm');
    const resetPageTextsBtn = document.getElementById('resetPageTexts');
    
    let currentPageData = null;
    
    // Page selection
    pageSelectors.forEach(selector => {
        selector.addEventListener('click', function() {
            const pageType = this.dataset.page;
            loadPageTexts(pageType);
            
            // Update active state
            pageSelectors.forEach(s => s.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Load page texts
    function loadPageTexts(pageType) {
        fetch(`{{ route('gym-branding.page-texts', [$siteSetting->id, 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', pageType))
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    currentPageData = data.data;
                    displayTextFields(data.data.texts, pageType);
                    textCustomizationArea.classList.remove('d-none');
                } else {
                    toastr.error('Error loading page texts: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                console.error('URL:', `{{ route('gym-branding.page-texts', [$siteSetting->id, 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', pageType));
                toastr.error('An error occurred while loading page texts: ' + error.message);
            });
    }
    
    // Display text fields
    function displayTextFields(texts, pageType) {
        const pageNames = {
            'login': 'Login Page',
            'register': 'Register Page',
            'auth_common': 'Auth Pages (Common)',
            'home': 'Home Page',
            'about': 'About Us Page',
            'services': 'Services Page',
            'contact': 'Contact Us Page',
            'blog': 'Blog Page',
            'team': 'Our Team Page',
            'gallery': 'Gallery Page'
        };
        
        selectedPageTitle.textContent = `Customize ${pageNames[pageType]} Texts`;
        currentPageType.value = pageType;
        
        textFieldsContainer.innerHTML = '';
        
        Object.entries(texts).forEach(([key, value]) => {
            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'form-group mb-4';
            
            const label = document.createElement('label');
            label.className = 'form-label fw-bold';
            label.textContent = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            label.setAttribute('for', `text_${key}`);
            
            // Determine if this should be a textarea or input
            const shouldBeTextarea = key.includes('description') || key.includes('text') || key.includes('subtitle');
            
            let input;
            if (shouldBeTextarea) {
                input = document.createElement('textarea');
                input.rows = 4;
            } else {
                input = document.createElement('input');
                input.type = 'text';
            }
            
            input.className = 'form-control';
            input.id = `text_${key}`;
            input.name = `texts[${key}]`;
            input.value = value || '';
            input.placeholder = `Enter ${key.replace(/_/g, ' ')}...`;
            
            fieldDiv.appendChild(label);
            fieldDiv.appendChild(input);
            textFieldsContainer.appendChild(fieldDiv);
        });
    }
    
    
    // Reset page texts
    resetPageTextsBtn.addEventListener('click', function() {
        if (!currentPageData) return;
        
        if (confirm(`Are you sure you want to reset ${currentPageData.page_type} page texts to defaults?`)) {
            fetch(`{{ route('gym-branding.reset-page-texts', [$siteSetting->id, 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', currentPageData.page_type), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Page texts reset to defaults!');
                    loadPageTexts(currentPageData.page_type);
                } else {
                    toastr.error('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while resetting page texts.');
            });
        }
    });
    
    // Submit texts form
    textsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        submitForm(this, 'page_texts');
        return false;
    });
    
    // Repeater fields functionality
    const repeaterSelectors = document.querySelectorAll('.repeater-selector');
    const repeaterCustomizationArea = document.getElementById('repeaterCustomizationArea');
    const selectedRepeaterTitle = document.getElementById('selectedRepeaterTitle');
    const currentRepeaterSection = document.getElementById('currentRepeaterSection');
    const repeaterFieldsContainer = document.getElementById('repeaterFieldsContainer');
    const repeatersForm = document.getElementById('repeatersForm');
    const resetRepeatersBtn = document.getElementById('resetRepeaters');
    
    let currentRepeaterData = null;
    let currentRepeaterConfig = null;
    
    // Repeater selection
    repeaterSelectors.forEach(selector => {
        selector.addEventListener('click', function() {
            const section = this.dataset.section;
            loadRepeaterFields(section);
            
            // Update active state
            repeaterSelectors.forEach(s => s.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Load repeater fields
    function loadRepeaterFields(section) {
        fetch(`{{ route('gym-branding.repeater-fields', [$siteSetting->id, 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', section))
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    currentRepeaterData = data.data;
                    currentRepeaterConfig = getRepeaterConfig(section);
                    displayRepeaterFields(data.data.data, section);
                    repeaterCustomizationArea.classList.remove('d-none');
                } else {
                    toastr.error('Error loading repeater fields: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while loading repeater fields: ' + error.message);
            });
    }
    
    // Get repeater config from the page data
    function getRepeaterConfig(section) {
        const repeaterConfigs = @json($repeaterConfigs);
        return repeaterConfigs[section] || null;
    }
    
    // Display repeater fields
    function displayRepeaterFields(data, section) {
        const config = getRepeaterConfig(section);
        if (!config) return;
        
        selectedRepeaterTitle.textContent = `Customize ${config.title}`;
        currentRepeaterSection.value = section;
        
        repeaterFieldsContainer.innerHTML = '';
        
        // Display existing items
        data.forEach((item, index) => {
            addRepeaterItem(item, index, config);
        });
        
        // Add "Add Item" button if under max limit
        if (data.length < config.max_items) {
            addAddItemButton(config);
        }
    }
    
    // Add a repeater item
    function addRepeaterItem(item = {}, index = 0, config) {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'repeater-item';
        itemDiv.setAttribute('data-index', index);
        
        const header = document.createElement('div');
        header.className = 'repeater-item-header';
        header.innerHTML = `
            <div class="repeater-item-title">Item ${index + 1}</div>
            <div class="repeater-item-actions">
                <button type="button" class="btn btn-sm btn-outline-danger remove-repeater-item">
                    <i class="ki-duotone ki-trash fs-2"></i>Remove
                </button>
            </div>
        `;
        
        const fieldsDiv = document.createElement('div');
        fieldsDiv.className = 'repeater-fields';
        
        // Add fields based on config
        Object.entries(config.fields).forEach(([fieldKey, fieldConfig]) => {
            const fieldGroup = document.createElement('div');
            fieldGroup.className = 'repeater-field-group';
            
            const label = document.createElement('label');
            label.textContent = fieldConfig.label;
            if (fieldConfig.required) {
                label.innerHTML += ' <span class="text-danger">*</span>';
            }
            
            let input;
            if (fieldConfig.type === 'textarea') {
                input = document.createElement('textarea');
                input.rows = 3;
            } else if (fieldConfig.type === 'select') {
                input = document.createElement('select');
                if (fieldConfig.options) {
                    fieldConfig.options.forEach(option => {
                        const optionEl = document.createElement('option');
                        optionEl.value = option.value;
                        optionEl.textContent = option.label;
                        input.appendChild(optionEl);
                    });
                }
            } else {
                input = document.createElement('input');
                input.type = fieldConfig.type || 'text';
            }
            
            input.className = 'form-control';
            input.name = `repeater_data[${index}][${fieldKey}]`;
            input.value = item[fieldKey] || '';
            input.placeholder = fieldConfig.placeholder || '';
            
            fieldGroup.appendChild(label);
            fieldGroup.appendChild(input);
            
            // Add help text if available
            if (fieldConfig.help_text) {
                const helpDiv = document.createElement('div');
                helpDiv.className = 'form-text text-muted small';
                helpDiv.innerHTML = fieldConfig.help_text;
                fieldGroup.appendChild(helpDiv);
            }
            
            fieldsDiv.appendChild(fieldGroup);
        });
        
        itemDiv.appendChild(header);
        itemDiv.appendChild(fieldsDiv);
        repeaterFieldsContainer.appendChild(itemDiv);
        
        // Add remove functionality
        const removeBtn = itemDiv.querySelector('.remove-repeater-item');
        removeBtn.addEventListener('click', function() {
            itemDiv.remove();
            updateItemIndexes();
            checkAddButtonVisibility(config);
        });
    }
    
    // Add "Add Item" button
    function addAddItemButton(config) {
        const addButton = document.createElement('div');
        addButton.className = 'add-repeater-item';
        addButton.innerHTML = `
            <i class="ki-duotone ki-plus fs-2"></i>
            <div>Add New Item</div>
            <small class="text-muted">Click to add another item (${getCurrentItemCount()}/${config.max_items})</small>
        `;
        
        addButton.addEventListener('click', function() {
            const newIndex = getCurrentItemCount();
            addRepeaterItem({}, newIndex, config);
            this.remove();
            checkAddButtonVisibility(config);
        });
        
        repeaterFieldsContainer.appendChild(addButton);
    }
    
    // Get current item count
    function getCurrentItemCount() {
        return document.querySelectorAll('.repeater-item').length;
    }
    
    // Update item indexes after removal
    function updateItemIndexes() {
        const items = document.querySelectorAll('.repeater-item');
        items.forEach((item, index) => {
            item.setAttribute('data-index', index);
            item.querySelector('.repeater-item-title').textContent = `Item ${index + 1}`;
            
            // Update input names
            const inputs = item.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                const name = input.name;
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                input.name = newName;
            });
        });
    }
    
    // Check if add button should be visible
    function checkAddButtonVisibility(config) {
        const currentCount = getCurrentItemCount();
        const addButton = document.querySelector('.add-repeater-item');
        
        if (currentCount < config.max_items && !addButton) {
            addAddItemButton(config);
        } else if (currentCount >= config.max_items && addButton) {
            addButton.remove();
        }
    }
    
    
    // Reset repeaters
    resetRepeatersBtn.addEventListener('click', function() {
        if (!currentRepeaterData) return;
        
        if (confirm(`Are you sure you want to reset ${currentRepeaterData.section} repeater fields to defaults?`)) {
            fetch(`{{ route('gym-branding.reset-repeater-fields', [$siteSetting->id, 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', currentRepeaterData.section), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Repeater fields reset to defaults!');
                    loadRepeaterFields(currentRepeaterData.section);
                } else {
                    toastr.error('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while resetting repeater fields.');
            });
        }
    });
    
    // Submit repeaters form
    repeatersForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Collect repeater data properly
        const formData = new FormData(this);
        const repeaterData = [];
        
        // Collect all repeater items
        const items = document.querySelectorAll('.repeater-item');
        items.forEach((item, index) => {
            const itemData = {};
            const inputs = item.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                const fieldName = input.name.match(/\[([^\]]+)\]$/)[1];
                itemData[fieldName] = input.value;
            });
            repeaterData.push(itemData);
        });
        
        // Add the collected data to form data
        formData.set('repeater_data', JSON.stringify(repeaterData));
        
        // Submit the form
        submitRepeaterForm(formData);
        return false;
    });
    
    // Custom submit function for repeater forms
    function submitRepeaterForm(formData) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
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
                toastr.success('Repeater fields updated successfully!');
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                toastr.error('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while saving the repeater fields: ' + error.message);
        });
    }
});
</script>
@endsection


