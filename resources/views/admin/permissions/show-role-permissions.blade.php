@extends('layout.admin.master')

@section('title', 'Manage Role Permissions')

@section('main-breadcrumb', 'Permissions')
@section('main-breadcrumb-link', route('admin.permissions.index'))

@section('sub-breadcrumb','Manage Role Permissions')

@section('toolbar-actions')

    <div class="col-auto float-end ms-auto">
        <a href="{{ route('admin.permissions.role-permissions') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Roles
        </a>
    </div>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Assign Permissions to {{ ucfirst($role->name) }} Role</h5>
                    <p class="card-text">Select the permissions you want to assign to this role. These permissions will only apply to users with this role in your gym.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.assign-role-permissions', $role) }}" method="POST">
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
                                                $isAssigned = $rolePermissions->contains('name', $permissionKey);
                                            @endphp
                                            
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="permission_{{ $permissionKey }}" 
                                                       name="permission_ids[]" 
                                                       value="{{ $permissionKey }}"
                                                       {{ $isAssigned ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permissionKey }}">
                                                    <strong>{{ $permissionLabel }}</strong>
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
                                    <i class="fas fa-save"></i> Update Role Permissions
                                </button>
                            @endcan
                            <a href="{{ route('admin.permissions.role-permissions') }}" class="btn btn-secondary">
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
                    <h5 class="card-title">Role Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Role Name:</strong>
                        <p class="mb-0">{{ ucfirst($role->name) }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Currently Assigned Permissions:</strong>
                        @if($rolePermissions->count() > 0)
                            <div class="mt-2">
                                @foreach($rolePermissions as $permission)
                                    <span class="badge bg-success me-1 mb-1">{{ $permission->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No custom permissions assigned</p>
                        @endif
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Important Notes</h6>
                        <ul class="mb-0">
                            <li>These permissions are gym-specific</li>
                            <li>Users inherit permissions from their roles</li>
                            <li>Individual user permissions override role permissions</li>
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

    $(document).ready(function() {
        // Add confirmation before form submission
        $('form').on('submit', function(e) {
            const checkedPermissions = $('input[name="permission_ids[]"]:checked').length;
            if (checkedPermissions === 0) {
                if (!confirm('You haven\'t selected any permissions. Are you sure you want to remove all permissions from this role?')) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
@endsection
