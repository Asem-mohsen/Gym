@extends('layout.user.master')

@section('title', 'Edit Profile')

@section('css')
    @include('user.profile.assets.style')
@endsection

@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Edit Profile</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home', ['siteSetting' => $siteSetting]) }}">Home</a>
                            <a href="{{ route('profile.index', ['siteSetting' => $siteSetting]) }}">Profile</a>
                            <span>Edit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Edit Profile Section Begin -->
    <section class="edit-profile-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="edit-profile-header">
                        <h3><i class="fa fa-edit"></i> Edit Profile Information</h3>
                    </div>
                    
                    <div class="edit-profile-form">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('profile.update', ['siteSetting' => $siteSetting]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $user->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" 
                                                id="gender" 
                                                name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">New Password (leave blank to keep current)</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>

                            <div class="form-group">
                                <label for="image">Profile Image</label>
                                <input type="file" 
                                       class="form-control-file @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                            </div>

                            <!-- Photo Gallery Section -->
                            <div class="form-group">
                                <label>Profile Photos Gallery</label>
                                <div class="photo-gallery-upload">
                                    <label for="photos" class="upload-area" id="photoUploadArea">
                                        <div class="upload-content">
                                            <i class="fa fa-cloud-upload-alt"></i>
                                            <p>Click to upload photos or drag and drop</p>
                                            <small>JPG, PNG, GIF up to 5MB each</small>
                                        </div>
                                        <input type="file" 
                                               id="photos" 
                                               name="photos[]" 
                                               multiple 
                                               accept="image/*" 
                                               style="display: none;">
                                    </label>
                                    
                                    <!-- Current Photos Display -->
                                    @if($user->photos->count() > 0)
                                    <div class="current-photos mt-3">
                                        <h6>Current Photos:</h6>
                                        <div class="photos-grid">
                                            @foreach($user->photos as $photo)
                                            <div class="photo-item" data-photo-id="{{ $photo->id }}">
                                                <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}" class="photo-thumbnail">
                                                <div class="photo-overlay">
                                                    <button type="button" class="btn btn-sm btn-danger delete-photo" data-photo-id="{{ $photo->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="photo-info">
                                                    <input type="text" 
                                                           name="photo_titles[{{ $photo->id }}]" 
                                                           value="{{ $photo->title }}" 
                                                           placeholder="Photo title" 
                                                           class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Preview Area for New Photos -->
                                    <div class="photos-preview mt-3" id="photosPreview" style="display: none;">
                                        <h6>New Photos:</h6>
                                        <div class="photos-grid" id="newPhotosGrid"></div>
                                    </div>
                                </div>
                                @error('photos')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('photos.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-save"></i> Update Profile
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('profile.index', ['siteSetting' => $siteSetting]) }}" class="btn btn-secondary btn-block">
                                            <i class="fa fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Edit Profile Section End -->
@endsection

@section('Js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('photoUploadArea');
    const fileInput = document.getElementById('photos');
    const photosPreview = document.getElementById('photosPreview');
    const newPhotosGrid = document.getElementById('newPhotosGrid');
    
    console.log('Elements found:', {
        uploadArea: !!uploadArea,
        fileInput: !!fileInput,
        photosPreview: !!photosPreview,
        newPhotosGrid: !!newPhotosGrid
    });
    
    // Debug: Check if elements exist
    if (!uploadArea) {
        return;
    }
    if (!fileInput) {
        return;
    }
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        handleFiles(files);
    });
    
    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });
    
    // Handle selected files
    function handleFiles(files) {
        if (files.length === 0) return;
        
        // Clear previous previews
        newPhotosGrid.innerHTML = '';
        photosPreview.style.display = 'block';
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoItem = document.createElement('div');
                    photoItem.className = 'photo-item';
                    photoItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="photo-thumbnail">
                        <div class="photo-overlay">
                            <button type="button" class="btn btn-sm btn-danger remove-preview" data-index="${index}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="photo-info">
                            <input type="text" name="new_photo_titles[]" placeholder="Photo title" class="form-control form-control-sm">
                        </div>
                    `;
                    newPhotosGrid.appendChild(photoItem);
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Remove preview photo
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-preview')) {
            const button = e.target.closest('.remove-preview');
            const photoItem = button.closest('.photo-item');
            photoItem.remove();
            
            // Hide preview section if no photos left
            if (newPhotosGrid.children.length === 0) {
                photosPreview.style.display = 'none';
            }
        }
    });
    
    // Delete existing photo
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-photo')) {
            const button = e.target.closest('.delete-photo');
            const photoId = button.dataset.photoId;
            
            if (confirm('Are you sure you want to delete this photo?')) {
                // Create a hidden input to mark photo for deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_photos[]';
                deleteInput.value = photoId;
                document.querySelector('form').appendChild(deleteInput);
                
                // Remove the photo item from display
                const photoItem = button.closest('.photo-item');
                photoItem.remove();
            }
        }
    });
});
</script>
@endsection
