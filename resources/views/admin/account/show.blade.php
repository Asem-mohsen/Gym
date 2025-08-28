@extends('layout.admin.master')

@section('title', 'Account Details')

@section('main-breadcrumb', 'Account')
@section('main-breadcrumb-link', route('admin.account.show'))

@section('sub-breadcrumb', 'Details')

@section('content')

<!-- Account Details Card -->
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.account.update') }}">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="row mb-8">
                <div class="col-12">
                    <h4 class="mb-4">Basic Information</h4>
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="name" class="form-label required">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control form-control-solid @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="email" class="form-label required">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control form-control-solid @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="phone" class="form-label required">Phone Number</label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           class="form-control form-control-solid @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $user->phone) }}" 
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="gender" class="form-label required">Gender</label>
                    <select id="gender" 
                            name="gender" 
                            class="form-select form-select-solid @error('gender') is-invalid @enderror" 
                            required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 mb-6">
                    <label for="address" class="form-label required">Address</label>
                    <textarea id="address" 
                              name="address" 
                              class="form-control form-control-solid @error('address') is-invalid @enderror" 
                              rows="3" 
                              required>{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Password Change -->
            <div class="row mb-8">
                <div class="col-12">
                    <h4 class="mb-4">Change Password (Optional)</h4>
                    <p class="text-muted">Leave blank if you don't want to change your password</p>
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control form-control-solid @error('password') is-invalid @enderror" 
                           minlength="8">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control form-control-solid @error('password_confirmation') is-invalid @enderror" 
                           minlength="8">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Trainer Information (if user has trainer information or is a trainer) -->
            @if($user->hasRole('trainer') || $user->trainerInformation)
            <div class="row mb-8">
                <div class="col-12">
                    <h4 class="mb-4">Trainer Information</h4>
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="weight" class="form-label">Weight (kg)</label>
                    <input type="number" 
                           id="weight" 
                           name="weight" 
                           step="0.01" 
                           min="0" 
                           max="999.99"
                           class="form-control form-control-solid @error('weight') is-invalid @enderror" 
                           value="{{ old('weight', $user->trainerInformation?->weight) }}" 
                           placeholder="e.g., 75.5">
                    @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="height" class="form-label">Height (cm)</label>
                    <input type="number" 
                           id="height" 
                           name="height" 
                           step="0.01" 
                           min="0" 
                           max="999.99"
                           class="form-control form-control-solid @error('height') is-invalid @enderror" 
                           value="{{ old('height', $user->trainerInformation?->height) }}" 
                           placeholder="e.g., 175.0">
                    @error('height')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" 
                           id="date_of_birth" 
                           name="date_of_birth" 
                           class="form-control form-control-solid @error('date_of_birth') is-invalid @enderror" 
                           value="{{ old('date_of_birth', $user->trainerInformation?->date_of_birth?->format('Y-m-d')) }}">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 mb-6">
                    <label for="brief_description" class="form-label">Brief Description / Highlights</label>
                    <textarea id="brief_description" 
                              name="brief_description" 
                              class="form-control form-control-solid @error('brief_description') is-invalid @enderror" 
                              rows="4" 
                              placeholder="Tell us about your experience, specialties, achievements...">{{ old('brief_description', $user->trainerInformation?->brief_description) }}</textarea>
                    @error('brief_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="facebook_url" class="form-label">Facebook URL</label>
                    <input type="url" 
                           id="facebook_url" 
                           name="facebook_url" 
                           class="form-control form-control-solid @error('facebook_url') is-invalid @enderror" 
                           value="{{ old('facebook_url', $user->trainerInformation?->facebook_url) }}" 
                           placeholder="https://facebook.com/yourprofile">
                    @error('facebook_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="twitter_url" class="form-label">Twitter URL</label>
                    <input type="url" 
                           id="twitter_url" 
                           name="twitter_url" 
                           class="form-control form-control-solid @error('twitter_url') is-invalid @enderror" 
                           value="{{ old('twitter_url', $user->trainerInformation?->twitter_url) }}" 
                           placeholder="https://twitter.com/yourprofile">
                    @error('twitter_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="instagram_url" class="form-label">Instagram URL</label>
                    <input type="url" 
                           id="instagram_url" 
                           name="instagram_url" 
                           class="form-control form-control-solid @error('instagram_url') is-invalid @enderror" 
                           value="{{ old('instagram_url', $user->trainerInformation?->instagram_url) }}" 
                           placeholder="https://instagram.com/yourprofile">
                    @error('instagram_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-6">
                    <label for="youtube_url" class="form-label">YouTube URL</label>
                    <input type="url" 
                           id="youtube_url" 
                           name="youtube_url" 
                           class="form-control form-control-solid @error('youtube_url') is-invalid @enderror" 
                           value="{{ old('youtube_url', $user->trainerInformation?->youtube_url) }}" 
                           placeholder="https://youtube.com/yourchannel">
                    @error('youtube_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif
            
            <!-- Submit Button -->
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Update Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
