@extends('layout.admin.master')

@section('title' , 'Admins')

@section('page-title', 'Admins')

@section('main-breadcrumb', 'Admins')
@section('main-breadcrumb-link', route('admins.index'))

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

            @can('create_admins')
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                        <a href="{{ route('admins.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add admin</a>
                    </div>
                </div>
            @endcan

        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Admin</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($admins as $key => $admin)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        <img src="{{ $admin->user_image}}" class="avatar avatar-sm me-3" alt="user1">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{$admin->name}}</h6>
                                        <small class="text-muted">{{$admin->email}}</small>
                                        <small class="text-muted">{{$admin->phone}}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($admin->roles as $role)
                                    <span class="badge bg-primary text-white">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($admin->status)
                                    <x-badge 
                                        :color="'success'" 
                                        content="Active"
                                    />
                                @else
                                    <x-badge 
                                        :color="'danger'" 
                                        content="Disactivated"
                                    />
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('edit_admins')
                                        <x-table-icon-link 
                                            :route="route('admins.edit',$admin->id)" 
                                            colorClass="primary"
                                            title="Edit"
                                            iconClasses="fa-solid fa-pen"
                                        />
                                    @endcan
                                    @can('view_admins')
                                        <x-table-icon-link 
                                            :route="route('admins.show',$admin->id)" 
                                            colorClass="success"
                                            title="View"
                                            iconClasses="fa-solid fa-eye"
                                        />
                                    @endcan
                                    @can('edit_admins')
                                        @if(!$admin->has_set_password)
                                            <form action="{{ route('admins.resend-onboarding-email', $admin->id) }}" method="post" style="display: inline;">
                                                @csrf
                                                <x-icon-button
                                                    colorClass="warning"
                                                    title="Resend Onboarding Email"
                                                    iconClasses="fa-solid fa-envelope"
                                                    onclick="return confirm('Are you sure you want to resend the onboarding email to {{ $admin->name }}?')"
                                                />
                                            </form>
                                        @endif
                                    @endcan
                                    @can('delete_admins')
                                        <form action="{{ route('admins.destroy' ,$admin->id )}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <x-icon-button
                                                colorClass="danger"
                                                title="Delete"
                                                iconClasses="fa-solid fa-trash"
                                            />
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('_partials.dataTable-script')
@endsection