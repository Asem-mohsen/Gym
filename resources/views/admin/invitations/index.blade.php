@extends('layout.admin.master')

@section('title', 'Invitations Management')

@section('main-breadcrumb', 'Invitations')
@section('main-breadcrumb-link', route('invitations.index'))

@section('sub-breadcrumb', 'All Invitations')

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
                    
                    <!-- Branch Filter -->
                    <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2" id="filter-form">
                        
                        <!-- Membership Filter -->
                        <select name="membership_id" class="form-control form-control-solid w-300px" onchange="this.form.submit()">
                            <option value="">All Memberships</option>
                            @foreach($memberships as $membership)
                                <option value="{{ $membership->id }}" {{ request('membership_id') == $membership->id ? 'selected' : '' }}>
                                    {{ is_string($membership->name) ? $membership->name : $membership->getTranslation('name', app()->getLocale()) }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Status Filter -->
                        <select class="form-control form-control-solid w-200px" name="status" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>

                        <!-- Branch Filter -->
                        <select name="branch_id" class="form-control form-control-solid w-200px" onchange="this.form.submit()">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Clear Filters Button -->
                        <a href="{{ route('invitations.index') }}" class="btn btn-light-danger btn-sm">
                            Clear
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Invitations Table -->
        <div class="card-body pt-0">
            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>ID</th>
                        <th>Inviter</th>
                        <th>Membership</th>
                        <th>Invitee Name</th>
                        <th>Invitee Email</th>
                        <th>Invitee Phone</th>
                        <th>Status</th>
                        <th>Expires At</th>
                        <th>Sent At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach($invitations as $invitation)
                        <tr>
                            <td>{{ $invitation->id }}</td>
                            <td>{{ $invitation->inviter->name }}</td>
                            <td>{{ $invitation->membership->getTranslation('name', 'en') }}</td>
                            <td>{{ $invitation->invitee_name ?? 'N/A' }}</td>
                            <td>{{ $invitation->invitee_email }}</td>
                            <td>{{ $invitation->invitee_phone }}</td>
                            <td>
                                @if($invitation->is_used)
                                    <span class="badge badge-success">Used</span>
                                @elseif($invitation->isExpired())
                                    <span class="badge badge-warning">Expired</span>
                                @else
                                    <span class="badge badge-primary">Active</span>
                                @endif
                            </td>
                            <td>{{ $invitation->expires_at ? $invitation->expires_at->format('M j, Y H:i') : 'N/A' }}</td>
                            <td>{{ $invitation->created_at->format('M j, Y H:i') }}</td>
                            <td>
                                @can('view_invitations')
                                    <a href="{{ route('invitations.show', $invitation) }}" 
                                    class="btn  btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan
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