@extends('layout.admin.master')

@section('title', 'Roles')

@section('main-breadcrumb', 'Roles')
@section('main-breadcrumb-link', route('roles.index'))

@section('sub-breadcrumb', 'Index')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">

        <div class="card-header border-0 pt-6">

            <div class="card-title">

                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search" />
                </div>

            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Role</a>
                </div>
            </div>

        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Role</th>
                        <th>Number of Users</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key => $role)
                    <tr>
                        <td>
                            {{ ++$key }}
                        </td>
                        <td>
                            {{$role->name}}
                        </td>
                        <td>{{ $role->users_count }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <x-table-icon-link 
                                    :route="route('roles.edit',$role->id)" 
                                    colorClass="primary"
                                    title="Edit"
                                    iconClasses="fa-solid fa-pen"
                                />
                                <form action="{{ route('roles.destroy' ,$role->id )}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-icon-button
                                        colorClass="danger"
                                        title="Delete"
                                        iconClasses="fa-solid fa-trash"
                                    />
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
