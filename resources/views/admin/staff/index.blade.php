@extends('layout.admin.master')

@section('title', 'Staff Management')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Staff')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">

        <div class="card-header border-0 pt-6">

            @include('_partials.search-filter-bar', [
                'searchPlaceholder' => 'Search staff...',
                'filters' => [
                    [
                        'name' => 'branch_id',
                        'label' => 'Branch',
                        'options' => $branches,
                        'valueKey' => 'id',
                        'labelKey' => 'name',
                        'defaultLabel' => 'All Branches'
                    ]
                ]
            ])

            @can('create_staff')
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                        <a href="{{ route('staff.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Staff</a>
                    </div>
                </div>
            @endcan

        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Staff Member</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($staff as $key => $member)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        @if($member->user_image)
                                            <img src="{{ $member->user_image }}" class="avatar avatar-sm me-3" alt="{{ $member->name }}">
                                        @else
                                            <div class="avatar avatar-sm me-3 bg-light-primary text-primary fw-bold">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $member->name }}</h6>
                                        <small class="text-muted">{{ ucfirst($member->gender ?? 'Not specified') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone }}</td>
                            <td> 
                                @foreach($member->roles as $role)
                                    <span class="badge bg-primary text-white">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($member->status)
                                    <x-badge 
                                        :color="'success'" 
                                        content="Active"
                                    />
                                @else
                                    <x-badge 
                                        :color="'danger'" 
                                        content="Inactive"
                                    />
                                @endif
                            </td>
                            <td>{{ $member->created_at->format('d F Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('edit_staff')
                                        <x-table-icon-link 
                                            :route="route('staff.edit',$member->id)" 
                                            colorClass="primary"
                                            title="Edit"
                                            iconClasses="fa-solid fa-pen"
                                        />
                                    @endcan
                                    @can('view_staff')
                                        <x-table-icon-link 
                                            :route="route('staff.show',$member->id)" 
                                            colorClass="success"
                                            title="View"
                                            iconClasses="fa-solid fa-eye"
                                        />
                                    @endcan
                                    @can('delete_staff')
                                        @if(!$member->has_set_password)
                                            <form action="{{ route('staff.resend-onboarding-email', $member->id) }}" method="post" style="display: inline;">
                                                @csrf
                                                <x-icon-button
                                                    colorClass="warning"
                                                    title="Resend Onboarding Email"
                                                    iconClasses="fa-solid fa-envelope"
                                                    onclick="return confirm('Are you sure you want to resend the onboarding email to {{ $member->name }}?')"
                                                />
                                            </form>
                                        @endif
                                    @endcan
                                    @can('delete_staff')
                                        <form action="{{ route('staff.destroy' ,$member->id )}}" method="post">
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
