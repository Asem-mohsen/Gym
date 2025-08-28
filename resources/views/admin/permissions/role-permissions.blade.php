@extends('layout.admin.master')

@section('title', 'Role Permissions Management')

@section('main-breadcrumb', 'Permissions Management')
@section('main-breadcrumb-link', route('admin.permissions.index'))

@section('sub-breadcrumb', 'Role Permissions')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search" />
                    </div>
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                        <i class="ki-duotone ki-arrow-left fs-2"></i>Back to Permissions
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="row">
                @foreach($roles as $role)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card border">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ki-duotone ki-users fs-2"></i> {{ ucfirst($role->name) }} Role
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Assigned Permissions:</strong>
                                @if($role->permissions->count() > 0)
                                    <div class="mt-2">
                                        @foreach($role->permissions as $permission)
                                            <span class="badge bg-primary me-1 mb-1 text-white">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No custom permissions assigned</p>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between">
                                @can('manage_roles')
                                    <a href="{{ route('admin.permissions.show-role-permissions', $role) }}" class="btn btn-sm btn-primary">
                                        <i class="ki-duotone ki-pencil fs-2"></i> Manage Permissions
                                    </a>
                                @else
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-primary">
                                        <i class="ki-duotone ki-arrow-left fs-2"></i> Back to Permissions
                                    </a>
                                @endcan
                                <span class="badge bg-secondary">{{ $role->users_count ?? 0 }} users</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
