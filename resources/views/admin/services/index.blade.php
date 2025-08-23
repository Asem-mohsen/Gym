@extends('layout.admin.master')

@section('title', 'Services')

@section('main-breadcrumb', 'Services')
@section('main-breadcrumb-link', route('services.index'))

@section('sub-breadcrumb', 'Index')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
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
                        <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-12" placeholder="Search services..." value="{{ request('search') }}" />
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
                    <a href="{{ route('services.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Service</a>
                </div>
            </div>

        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Service</th>
                        <th>Description</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Booking Type</th>
                        <th>Branches</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($services as $key => $service)
                        <tr>
                            <td>
                                {{ ($services->currentPage() - 1) * $services->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $service->getTranslation('name', 'en') }}</span>
                                    <small class="text-muted">{{ $service->getTranslation('name', 'ar') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ \Illuminate\Support\Str::limit($service->getTranslation('description', 'en'), 50, '...') }}</span>
                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($service->getTranslation('description', 'ar'), 50, '...') }}</small>
                                </div>
                            </td>
                            <td>
                                @if($service->duration > 0)
                                    {{ $service->duration }} min
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($service->price > 0)
                                    {{ number_format($service->price, 2) }} EGP
                                @else
                                    <span class="text-success">Free</span>
                                @endif
                            </td>
                            <td>
                                @switch($service->booking_type)
                                    @case('unbookable')
                                        <span class="badge badge-light-info">Unbookable</span>
                                        @break
                                    @case('free_booking')
                                        <span class="badge badge-light-success">Free Booking</span>
                                        @break
                                    @case('paid_booking')
                                        <span class="badge badge-light-warning">
                                            Paid Booking
                                            @if($service->booking_fee)
                                                <br><small>{{ number_format($service->booking_fee, 2) }} EGP</small>
                                            @endif
                                        </span>
                                        @break
                                    @default
                                        <span class="badge badge-light-secondary">Unknown</span>
                                @endswitch
                            </td>
                            <td>
                                @if($service->branches->count() > 0)
                                    <div class="d-flex flex-column">
                                        @foreach($service->branches->take(2) as $branch)
                                            <small>{{ $branch->getTranslation('name', 'en') }}</small>
                                        @endforeach
                                        @if($service->branches->count() > 2)
                                            <small class="text-muted">+{{ $service->branches->count() - 2 }} more</small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">All Branches</span>
                                @endif
                            </td>
                            <td>
                                @if($service->is_available)
                                    <span class="badge badge-light-success">Available</span>
                                @else
                                    <span class="badge badge-light-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-table-icon-link 
                                        :route="route('services.edit',$service->id)" 
                                        colorClass="primary"
                                        title="Edit"
                                        iconClasses="fa-solid fa-pen"
                                    />
                                    <x-table-icon-link 
                                        :route="route('services.show',$service->id)" 
                                        colorClass="success"
                                        title="View"
                                        iconClasses="fa-solid fa-eye"
                                    />
                                    <form action="{{ route('services.destroy' ,$service->id )}}" method="post">
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
                <nav aria-label="Services pagination">
                    {{ $services->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('_partials.dataTable-script')
@endsection