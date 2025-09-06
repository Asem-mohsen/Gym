@extends('layout.admin.master')

@section('title', 'Add New Staff Member')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Staff')
@section('sub-breadcrumb-link', route('staff.index'))

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Staff Member</h3>
    </div>
    
    <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="card-body">
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Basic Information</h4>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="col-md-6">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Additional Information</h4>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Accepted formats: JPG, PNG, GIF. Max size: 2MB</div>
                    </div>
                </div>
            </div>
            
            <!-- Role Assignment -->
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Role Assignment</h4>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Roles <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach($staffRoles as $role)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input @error('role_ids') is-invalid @enderror" type="checkbox" 
                                           name="role_ids[]" value="{{ $role['id'] }}" id="role_{{ $role['id'] }}"
                                           {{ in_array($role['id'], old('role_ids', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role['id'] }}">
                                        {{ ucfirst($role['name']) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('role_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Select one or more roles for this staff member</div>
                    </div>
                </div>
            </div>
            
            <!-- Branch Assignment -->
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="fs-5 fw-bold text-gray-800 mb-3">Branch Assignment</h4>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Branches <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach($branches as $branch)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input @error('branch_ids') is-invalid @enderror" type="checkbox" 
                                           name="branch_ids[]" value="{{ $branch['id'] }}" id="branch_{{ $branch['id'] }}"
                                           {{ in_array($branch['id'], old('branch_ids', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="branch_{{ $branch['id'] }}">
                                        {{ $branch['name'] }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('branch_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Select one or more branches for this staff member</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('staff.index') }}" class="btn btn-light">
                    <i class="ki-duotone ki-arrow-left fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Back to Staff
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Create Staff Member
                </button>
            </div>
        </div>
    </form>
</div>

@endsection