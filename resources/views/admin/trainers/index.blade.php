@extends('layout.admin.master')

@section('title', 'Trainers Management')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Trainers')

@section('content')

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
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                @can('create_trainers')
                <a href="{{ route('trainers.create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Add Trainer
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card-body py-4">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Trainer</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Address</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($trainers as $trainer)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td class="d-flex align-items-center">
                            <div class="symbol symbol-45px me-5">
                                @if($trainer->user_image)
                                    <img src="{{ $trainer->user_image }}" alt="{{ $trainer->name }}" />
                                @else
                                    <div class="symbol-label fs-3 fw-semibold bg-light-primary text-primary">
                                        {{ strtoupper(substr($trainer->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-start flex-column">
                                <a href="{{ route('trainers.show', $trainer) }}" class="text-dark fw-bold text-hover-primary fs-6">{{ $trainer->name }}</a>
                                <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $trainer->email }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-dark fw-bold text-hover-primary fs-6">{{ $trainer->phone }}</span>
                                <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $trainer->gender }}</span>
                            </div>
                        </td>
                        <td>
                            @if($trainer->status)
                                <div class="badge badge-light-success">Active</div>
                            @else
                                <div class="badge badge-light-danger">Inactive</div>
                            @endif
                        </td>
                        <td>
                            {{ $trainer->address }}
                        </td>
                        <td>{{ $trainer->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('view_trainers')
                                    <x-table-icon-link 
                                        :route="route('trainers.show',$trainer->id)" 
                                        colorClass="success"
                                        title="View"
                                        iconClasses="fa-solid fa-eye"
                                    />
                                @endcan
                                @can('edit_trainers')
                                    <x-table-icon-link 
                                        :route="route('trainers.edit',$trainer->id)" 
                                        colorClass="primary"
                                        title="Edit"
                                        iconClasses="fa-solid fa-pen"
                                    />
                                    @if(!$trainer->has_set_password)
                                        <x-table-icon-link 
                                            :route="route('trainers.resend-onboarding-email', $trainer->id)" 
                                            colorClass="warning"
                                            title="Resend Email"
                                            iconClasses="fa-solid fa-envelope"
                                        />
                                    @endif
                                @endcan
                                @can('delete_trainers')
                                    <form action="{{ route('trainers.destroy', $trainer->id) }}" method="POST" class="d-inline">
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
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No trainers found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('_partials.dataTable-script')
@endsection