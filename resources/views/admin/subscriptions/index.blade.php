@extends('layout.admin.master')

@section('title' , 'Subscriptions')

@section('main-breadcrumb', 'Subscriptions')
@section('main-breadcrumb-link', route('subscriptions.index'))

@section('sub-breadcrumb', 'Index')

@section('toolbar-actions')
    @can('create_subscriptions')
        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Manual Subscripton</a>
    @endcan
@endsection

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
                        <input type="hidden" name="search" id="search-hidden" value="{{ request('search') }}">
                        
                        <!-- Status Filter -->
                        <select name="status" class="form-control form-control-solid w-100px" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="about_to_expire" {{ request('status') == 'about_to_expire' ? 'selected' : '' }}>About to Expire</option>
                        </select>

                        <!-- Membership Filter -->
                        <select name="membership_id" class="form-control form-control-solid w-200px" onchange="this.form.submit()">
                            <option value="">All Memberships</option>
                            @foreach($memberships as $membership)
                                <option value="{{ $membership->id }}" {{ request('membership_id') == $membership->id ? 'selected' : '' }}>
                                    {{ is_string($membership->name) ? $membership->name : $membership->getTranslation('name', app()->getLocale()) }}
                                </option>
                            @endforeach
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

                        <!-- Date Range Filters -->
                        <input type="date" name="date_from" class="form-control form-control-solid w-150px" 
                               placeholder="From Date" value="{{ request('date_from') }}" onchange="this.form.submit()">
                        <input type="date" name="date_to" class="form-control form-control-solid w-150px" 
                               placeholder="To Date" value="{{ request('date_to') }}" onchange="this.form.submit()">

                        <!-- Clear Filters Button -->
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-light-danger btn-sm">
                            Clear
                        </a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Active Filters Summary -->
        @if(request('status') || request('membership_id') || request('branch_id') || request('date_from') || request('date_to'))
            <div class="card-body border-bottom">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="text-muted">Active Filters:</span>
                    @if(request('status'))
                        <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', request('status'))) }}</span>
                    @endif
                    @if(request('membership_id'))
                        @php
                            $selectedMembership = $memberships->firstWhere('id', request('membership_id'));
                            $membershipName = $selectedMembership ? (is_string($selectedMembership->name) ? $selectedMembership->name : $selectedMembership->getTranslation('name', app()->getLocale())) : 'Unknown';
                        @endphp
                        <span class="badge badge-info">{{ $membershipName }}</span>
                    @endif
                    @if(request('branch_id'))
                        @php
                            $selectedBranch = $branches->firstWhere('id', request('branch_id'));
                        @endphp
                        <span class="badge badge-success">{{ $selectedBranch ? $selectedBranch->name : 'Unknown Branch' }}</span>
                    @endif
                    @if(request('date_from') || request('date_to'))
                        <span class="badge badge-warning">
                            {{ request('date_from') ? 'From: ' . request('date_from') : '' }}
                            {{ request('date_from') && request('date_to') ? ' - ' : '' }}
                            {{ request('date_to') ? 'To: ' . request('date_to') : '' }}
                        </span>
                    @endif
                </div>
            </div>
        @endif
        
        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>User</th>
                        <th>Membership</th>
                        <th>Branch</th>
                        <th>From - To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($subscriptions as $key => $subscription)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        <img src="{{ $subscription->user->user_image }}" class="avatar avatar-sm me-3" alt="user1">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <span class="text-sm">ID: {{$subscription->user->id}}</span>
                                        <h6 class="mb-0 text-sm">{{$subscription->user->name}}</h6>
                                        <span>{{$subscription->user->email}}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ is_string($subscription->membership->name) ? $subscription->membership->name : $subscription->membership->getTranslation('name', app()->getLocale()) }}</td>
                            <td>{{$subscription->branch->name}}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <p class="text-xs font-weight-bold mb-0">From - {{ date('d-M-Y' , strtotime($subscription->start_date)) }}</p>
                                    <p class="text-xs font-weight-bold mb-0">To - {{ date('d-M-Y' , strtotime($subscription->end_date)) }}</p>
                                </div>
                            </td>
                            <td>
                                @if ($subscription->status == 'active')
                                    <x-badge 
                                        :color="'success'" 
                                        content="Active"
                                    />
                                @elseif ($subscription->status == 'pending')
                                    <x-badge 
                                        :color="'warning'" 
                                        content="Active"
                                    />
                                @elseif ($subscription->status == 'cancelled')
                                    <x-badge 
                                        :color="'danger'" 
                                        content="Cancelled"
                                    />
                                    <span class="badge badge-danger">Cancelled</span>
                                @elseif ($subscription->status == 'expired')
                                    <x-badge 
                                        :color="'info'" 
                                        content="Expired"
                                    />
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('edit_subscriptions')
                                    <x-table-icon-link 
                                        :route="route('subscriptions.edit',$subscription->id)" 
                                        colorClass="primary"
                                        title="Edit"
                                        iconClasses="fa-solid fa-pen"
                                    />
                                    @endcan
                                    @can('view_subscriptions')
                                    <x-table-icon-link 
                                        :route="route('subscriptions.show',$subscription->id)" 
                                        colorClass="success"
                                        title="View"
                                        iconClasses="fa-solid fa-eye"
                                    />
                                    @endcan
                                    @can('delete_subscriptions')
                                    <form action="{{ route('subscriptions.destroy' ,$subscription->id )}}" method="POST">
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
    
    <script>
        // Handle search input changes
        document.querySelector('[data-kt-table-filter="search"]').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('search-hidden').value = this.value;
                document.getElementById('filter-form').submit();
            }
        });

        // Handle date input changes with debounce
        let dateTimeout;
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            input.addEventListener('change', function() {
                clearTimeout(dateTimeout);
                dateTimeout = setTimeout(() => {
                    document.getElementById('filter-form').submit();
                }, 500);
            });
        });
    </script>
@endsection