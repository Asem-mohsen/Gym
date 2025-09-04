@extends('layout.admin.master')

@section('title', 'Send New Notification')

@section('main-breadcrumb', 'Notifications')
@section('main-breadcrumb-link', route('admin.notifications.index'))

@section('sub-breadcrumb', 'Create')

@section('css')
    @include('admin.notifications.assets.style')
@endsection

@section('content')

<div class="row">
    <div class="col-md-8 mb-md-5 mb-xl-10">
        <form action="{{ route('admin.notifications.store') }}" method="POST" id="notification-form">
            @csrf
            <div class="card">
                <div class="card-body row">   
                    <div class="mb-10 col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <label class="required form-label mb-0">Target Roles</label>
                                <small class="text-muted d-block" id="role-count">Select roles to send notification to</small>
                            </div>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="selectAllRoles()">
                                    <i class="fas fa-check-double me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearAllRoles()">
                                    <i class="fas fa-times me-1"></i>Clear All
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($roles as $role)
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="role-selection-card" data-role="{{ $role->name }}">
                                        <input type="checkbox" class="role-checkbox d-none" 
                                                id="role_{{ $role->name }}" name="target_roles[]" 
                                                value="{{ $role->name }}" {{ in_array($role->name, old('target_roles', [])) ? 'checked' : '' }}>
                                        <label for="role_{{ $role->name }}" class="role-card-label">
                                            <div class="role-card-content">
                                                <div class="role-icon">
                                                    @switch($role->name)
                                                        @case('regular_user')
                                                            <i class="fas fa-user text-primary"></i>
                                                            @break
                                                        @case('trainer')
                                                            <i class="fas fa-dumbbell text-success"></i>
                                                            @break
                                                        @case('sales')
                                                            <i class="fas fa-handshake text-warning"></i>
                                                            @break
                                                        @case('management')
                                                            <i class="fas fa-users-cog text-info"></i>
                                                            @break
                                                        @default
                                                            <i class="fas fa-user-tag text-secondary"></i>
                                                    @endswitch
                                                </div>
                                                <div class="role-name">
                                                    {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                                </div>
                                                <div class="role-description">
                                                    @switch($role->name)
                                                        @case('regular_user')
                                                            Gym members
                                                            @break
                                                        @case('trainer')
                                                            Fitness trainers
                                                            @break
                                                        @case('sales')
                                                            Sales team
                                                            @break
                                                        @case('management')
                                                            Management staff
                                                            @break
                                                        @default
                                                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                                    @endswitch
                                                </div>
                                                <div class="role-check-indicator">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('target_roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror   
                    </div>   
                    <div class="mb-10 col-md-6">
                        <label for="priority" class="required form-label">Priority Level</label>
                        <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority">
                            <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <!-- Subject -->
                    <div class="mb-10 col-md-6">
                        <label for="subject" class="required form-label">Subject</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Enter notification subject" maxlength="255" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="mb-10 col-md-12">
                        <label for="message" class="required form-label">Message</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Enter your notification message" maxlength="1000" required>{{ old('message') }}</textarea>
                        <div class="form-text">
                            <span id="char-count">0</span> / 1000 characters
                        </div>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Button (Optional) -->
                    <div class="mb-10 col-md-6">
                        <label for="action_text" class="form-label">Action Button Text (Optional)</label>
                        <input type="text" class="form-control @error('action_text') is-invalid @enderror" id="action_text" name="action_text" value="{{ old('action_text') }}" placeholder="e.g., View Details" maxlength="100">
                        @error('action_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="action_url" class="form-label">Action URL (Optional)</label>
                        <input type="url" class="form-control @error('action_url') is-invalid @enderror" id="action_url" name="action_url" value="{{ old('action_url') }}"  placeholder="https://example.com" maxlength="500">
                        @error('action_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Scheduling Options -->
                    <div class="mb-10 col-md-6">
                        <label for="scheduled_at" class="form-label">Schedule for Later (Optional)</label>
                        <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                        <div class="form-text">Leave empty to send immediately</div>
                        @error('scheduled_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="expires_at" class="form-label">Expires At (Optional)</label>
                        <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                        <div class="form-text">Leave empty for no expiration</div>
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane mr-2"></i> Send Notification</button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-dark">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body row">   
                <label class="form-label">Preview</label>
                <div class="card border">
                    <div class="card-body">
                        <div id="notification-preview">
                            <div class="text-muted text-center">Fill in the form above to see a preview</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @include('admin.notifications.assets.scripts-create')
@endsection