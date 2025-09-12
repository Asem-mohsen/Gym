@extends('layout.admin.master')

@section('title', 'Manage User Permissions')

@section('main-breadcrumb', 'Permissions')
@section('main-breadcrumb-link', route('admin.permissions.index'))

@section('sub-breadcrumb','Manage User Permissions')

@section('toolbar-actions')
<a href="{{ route('admin.permissions.user-permissions') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back to Users
</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header p-5">
                    <h5 class="card-title">Assign Permissions to {{ $user->name }}</h5>
                    <p class="card-text">Select the permissions you want to assign to this user. These permissions will override role permissions and are specific to your gym.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.assign-user-permissions', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            @foreach($permissionGroups as $groupKey => $group)
                            <div class="col-md-6 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-light align-content-center">
                                        <h6 class="mb-0">{{ $group['label'] }}</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($group['permissions'] as $permissionKey => $permissionLabel)
                                            @php
                                                $hasDirectPermission = $userPermissions->contains('name', $permissionKey);
                                                $hasRolePermission = $allUserPermissions->contains('name', $permissionKey) && !$hasDirectPermission;
                                                $isAssigned = $hasDirectPermission; // Only check direct permissions
                                            @endphp
                                            
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="permission_{{ $permissionKey }}" 
                                                       name="permission_ids[]" 
                                                       value="{{ $permissionKey }}"
                                                       {{ $isAssigned ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permissionKey }}">
                                                    <strong>{{ $permissionLabel }}</strong>
                                                    @if($hasDirectPermission)
                                                        <span class="badge bg-success ms-1 text-white">Direct</span>
                                                    @elseif($hasRolePermission)
                                                        <span class="badge bg-info ms-1 text-white">Role</span>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">{{ $permissionKey }}</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            @can('assign_roles')
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update User Permissions
                                </button>
                            @endcan
                            <a href="{{ route('admin.permissions.user-permissions') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">User Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Assigned Roles:</strong>
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <span class="badge bg-info me-1 text-white">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">No roles assigned</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Direct Permissions:</strong>
                        @if($userPermissions->count() > 0)
                            <div class="mt-1">
                                @foreach($userPermissions as $permission)
                                    <span class="badge bg-success me-1 mb-1 text-white">{{ $permission->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No direct permissions assigned</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Total Accessible Permissions:</strong>
                        @if($allUserPermissions->count() > 0)
                            <div class="mt-1">
                                @foreach($allUserPermissions as $permission)
                                    <span class="badge bg-primary me-1 mb-1 text-white">{{ $permission->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No permissions accessible</p>
                        @endif
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Permission Hierarchy</h6>
                        <ul class="mb-0">
                            <li><strong>Direct Permissions</strong> (Green): Assigned directly to user</li>
                            <li><strong>Role Permissions</strong> (Blue): Inherited from assigned roles</li>
                            <li>Direct permissions override role permissions</li>
                            <li>These permissions are gym-specific</li>
                        </ul>
                    </div>
                </div>
            </div>

            @can('assign_roles')
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAllPermissions()">
                                <i class="fas fa-check-square"></i> Select All
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAllPermissions()">
                                <i class="fas fa-square"></i> Deselect All
                            </button>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function selectAllPermissions() {
        $('input[name="permission_ids[]"]').prop('checked', true);
    }

    function deselectAllPermissions() {
        $('input[name="permission_ids[]"]').prop('checked', false);
    }
</script>
@endsection
