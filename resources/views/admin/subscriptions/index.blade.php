@extends('layout.admin.master')

@section('title' , 'Subscriptions')

@section('main-breadcrumb', 'Subscriptions')
@section('main-breadcrumb-link', route('subscriptions.index'))

@section('sub-breadcrumb', 'Index')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="row g-4">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="card border-danger">
            <div class="card-body text-center">
                <div class="text-danger mb-2">
                    <i class="far fa-star fa-2x"></i>
                </div>
                <h6 class="card-title">Total Subscriptions</h6>
                <h4 class="card-text fw-bold">{{ $counts['total'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="card border-info">
            <div class="card-body text-center">
                <div class="text-info mb-2">
                    <i class="far fa-envelope fa-2x"></i>
                </div>
                <h6 class="card-title">Active Subscriptions</h6>
                <h4 class="card-text fw-bold">{{ $counts['active'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="card border-success">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="far fa-flag fa-2x"></i>
                </div>
                <h6 class="card-title">Expired Subscriptions</h6>
                <h4 class="card-text fw-bold">{{ $counts['expired'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="card border-warning">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="far fa-copy fa-2x"></i>
                </div>
                <h6 class="card-title">Pending Subscriptions</h6>
                <h4 class="card-text fw-bold">{{ $counts['pending'] }}</h4>
            </div>
        </div>
    </div>
</div>

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
                        <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-12" placeholder="Search subscriptions..." value="{{ request('search') }}" />
                    </div>
                    
                    <!-- Branch Filter -->
                    <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2" id="filter-form">
                        <input type="hidden" name="search" id="search-hidden" value="{{ request('search') }}">
                        <select name="branch_id" class="form-control form-control-solid w-200px" onchange="this.form.submit()">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <!-- Per Page Selector -->
                        <select name="per_page" class="form-control form-control-solid w-100px" onchange="this.form.submit()">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                    <a href="{{ route('subscriptions.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Manual Subscripton</a>
                </div>
            </div>

        </div>
        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>User</th>
                        <th>Membership</th>
                        <th>From - To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($subscriptions as $key => $subscription)
                        <tr>
                            <td>
                                {{ ($subscriptions->currentPage() - 1) * $subscriptions->perPage() + $loop->iteration }}
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
                            <td>{{$subscription->membership->name}}</td>
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
                                    <x-table-icon-link 
                                        :route="route('subscriptions.edit',$subscription->id)" 
                                        colorClass="primary"
                                        title="Edit"
                                        iconClasses="fa-solid fa-pen"
                                    />
                                    <x-table-icon-link 
                                        :route="route('subscriptions.show',$subscription->id)" 
                                        colorClass="success"
                                        title="View"
                                        iconClasses="fa-solid fa-eye"
                                    />
                                    <form action="{{ route('subscriptions.destroy' ,$subscription->id )}}" method="POST">
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
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center align-items-center py-3 border-top">
                <nav aria-label="Subscriptions pagination">
                    {{ $subscriptions->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('_partials.dataTable-script')
@endsection