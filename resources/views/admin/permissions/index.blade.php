@extends('layout.admin.master')

@section('title', 'Permissions Management')

@section('main-breadcrumb', 'Permissions Management')
@section('main-breadcrumb-link', route('admin.permissions.index'))

@section('sub-breadcrumb', 'Index')

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
                    <div class="btn-group">
                        @can('view_roles')
                            <a href="{{ route('admin.permissions.role-permissions') }}" class="btn btn-primary">
                                <i class="ki-duotone ki-users fs-2"></i>Role Permissions
                            </a>
                            <a href="{{ route('admin.permissions.user-permissions') }}" class="btn btn-info">
                                <i class="ki-duotone ki-user fs-2"></i>User Permissions
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="row">
                @foreach($permissionGroups as $groupKey => $group)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border">
                        <div class="card-header bg-light align-content-center">
                            <h6 class="mb-0 align-content-center">{{ $group['label'] }}</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 d-grid" style="grid-template-columns: repeat(2, 1fr);">
                                @foreach($group['permissions'] as $permissionKey => $permissionLabel)
                                <li class="mb-2">
                                    <small class="text-muted">{{ $permissionKey }}</small>
                                    <br>
                                    <span class="badge bg-secondary">{{ $permissionLabel }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection