@extends('layout.admin.master')

@section('title', 'User Permissions Management')

@section('main-breadcrumb', 'Permissions Management')
@section('main-breadcrumb-link', route('admin.permissions.index'))

@section('sub-breadcrumb', 'User Permissions')

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
                @foreach($users as $user)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card border">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ki-duotone ki-user fs-2"></i> {{ $user->name }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Roles:</strong>
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-info me-1 text-white">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0">No roles assigned</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Custom Permissions:</strong>
                                @if($user->permissions->count() > 0)
                                    <div class="mt-1">
                                        @foreach($user->permissions as $permission)
                                            <span class="badge bg-primary me-1 mb-1 text-white">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No custom permissions assigned</p>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between">
                                @can('manage_roles')
                                    <a href="{{ route('admin.permissions.show-user-permissions', $user) }}" class="btn btn-sm btn-primary">
                                        <i class="ki-duotone ki-pencil fs-2"></i> Manage Permissions
                                    </a>
                                @else
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-primary">
                                        <i class="ki-duotone ki-arrow-left fs-2"></i> Back to Permissions
                                    </a>
                                @endcan
                                <span class="badge bg-secondary">{{ $user->permissions->count() }} custom permissions</span>
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
