@extends('layout.admin.master')

@section('title', 'Create Trainer')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Trainers')
@section('sub-breadcrumb-link', route('trainers.index'))

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('trainers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Trainer</h3>
            </div>
            <div class="card-body row">
                <!-- Basic Information -->
                <div class="col-12 mb-5">
                    <h4 class="mb-3">Basic Information</h4>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="name" class="required form-label">Name</label>
                    <input type="text" value="{{ old('name') }}" name="name" class="form-control form-control-solid required" required/>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="email" class="required form-label">Email address</label>
                    <input type="email" value="{{ old('email') }}" name="email" class="form-control form-control-solid required" required/>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="phone" class="required form-label">Phone</label>
                    <input type="text" value="{{ old('phone') }}" name="phone" class="form-control form-control-solid required" required/>
                    @error('phone')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="gender" class="required form-label">Gender</label>
                    @php
                        $options = [
                            ['value' => 'male', 'label' => 'Male'],
                            ['value' => 'female', 'label' => 'Female'],
                        ];
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'gender',
                        'id' => 'gender',
                        'selectedValue' => old('gender'),
                    ])
                    @error('gender')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-12">
                    <label for="address" class="required form-label">Address</label>
                    <textarea name="address" class="form-control form-control-solid" rows="3" required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="role_ids" class="required form-label">Role</label>
                    @php
                        $options = [];
                        foreach($trainerRoles as $role){
                            $options[] = [
                                'value' => $role['id'],
                                'label' => $role['name']
                            ];
                        }
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'role_ids[]',
                        'id' => 'role_ids',
                        'selectedValue' => old('role_ids'),
                        'multiple' => true
                    ])
                    @error('role_ids')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="image" class="form-label">Profile Image</label>
                    <input type="file" name="image" class="form-control form-control-solid" accept="image/*"/>
                    @error('image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Trainer Information -->
                <div class="col-12 mb-5 mt-5">
                    <h4 class="mb-3">Trainer Information</h4>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="weight" class="form-label">Weight (kg)</label>
                    <input type="number" step="0.01" value="{{ old('weight') }}" name="weight" class="form-control form-control-solid" placeholder="e.g., 75.5"/>
                    @error('weight')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="height" class="form-label">Height (cm)</label>
                    <input type="number" step="0.01" value="{{ old('height') }}" name="height" class="form-control form-control-solid" placeholder="e.g., 175.0"/>
                    @error('height')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" value="{{ old('date_of_birth') }}" name="date_of_birth" class="form-control form-control-solid"/>
                    @error('date_of_birth')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-12">
                    <label for="brief_description" class="form-label">Brief Description</label>
                    <textarea name="brief_description" class="form-control form-control-solid" rows="4" placeholder="Tell us about the trainer's experience, specialties, etc.">{{ old('brief_description') }}</textarea>
                    @error('brief_description')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Social Media Links -->
                <div class="col-12 mb-5 mt-5">
                    <h4 class="mb-3">Social Media Links</h4>
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="facebook_url" class="form-label">Facebook URL</label>
                    <input type="url" value="{{ old('facebook_url') }}" name="facebook_url" class="form-control form-control-solid" placeholder="https://facebook.com/username"/>
                    @error('facebook_url')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="twitter_url" class="form-label">Twitter URL</label>
                    <input type="url" value="{{ old('twitter_url') }}" name="twitter_url" class="form-control form-control-solid" placeholder="https://twitter.com/username"/>
                    @error('twitter_url')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="instagram_url" class="form-label">Instagram URL</label>
                    <input type="url" value="{{ old('instagram_url') }}" name="instagram_url" class="form-control form-control-solid" placeholder="https://instagram.com/username"/>
                    @error('instagram_url')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-10 col-md-6">
                    <label for="youtube_url" class="form-label">YouTube URL</label>
                    <input type="url" value="{{ old('youtube_url') }}" name="youtube_url" class="form-control form-control-solid" placeholder="https://youtube.com/channel/username"/>
                    @error('youtube_url')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="card-footer">
                <div class="d-flex justify-content-start">
                   <button type="submit" class="btn btn-primary">Create Trainer</button>
                    <a href="{{ route('trainers.index') }}" class="btn btn-light me-3">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('js')
    <script>
        // Add any JavaScript for form validation or dynamic behavior
        $(document).ready(function() {
            // Form validation
            $('form').on('submit', function() {
                var isValid = true;
                
                // Check required fields
                $('.required').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                return isValid;
            });
        });
    </script>
@endsection
