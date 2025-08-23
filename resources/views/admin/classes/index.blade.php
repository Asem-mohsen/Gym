@extends('layout.admin.master')

@section('title', 'Classes')
@section('main-breadcrumb', 'Classes')
@section('main-breadcrumb-link', route('classes.index'))

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
                            <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-12" placeholder="Search classes..." value="{{ request('search') }}" />
                        </div>
                        
                        <!-- Type Filter -->
                        <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2" id="filter-form">
                            <input type="hidden" name="search" id="search-hidden" value="{{ request('search') }}">
                            <select name="type" class="form-control form-control-solid w-200px" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                @foreach($classTypes as $classType)
                                    <option value="{{ $classType }}" {{ request('type') == $classType ? 'selected' : '' }}>
                                        {{ ucfirst($classType) }}
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
                        <a href="{{ route('classes.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add New Class</a>
                    </div>
                </div>

            </div>

            <div class="card-body pt-0">

                <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Trainers</th>
                            <th>Schedules</th>
                            <th>Pricing</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($classes as $key => $class)
                            <tr>
                                <td>{{ ($classes->currentPage() - 1) * $classes->perPage() + $loop->iteration }}</td>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->type }}</td>
                                <td>{{ ucfirst($class->status) }}</td>
                                <td>
                                    @foreach($class->trainers as $trainer)
                                        <span class="badge bg-secondary">{{ $trainer->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($class->schedules as $schedule)
                                        <div>{{ ucfirst($schedule->day) }}: {{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($class->pricings as $pricing)
                                        <div>{{ $pricing->price }} ({{ $pricing->duration }})</div>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <x-table-icon-link 
                                            :route="route('classes.edit',$class->id)" 
                                            colorClass="primary"
                                            title="Edit"
                                            iconClasses="fa-solid fa-pen"
                                        />
                                        <form action="{{ route('classes.destroy' ,$class->id )}}" method="post">
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
                    <nav aria-label="Classes pagination">
                        {{ $classes->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection 

@section('js')
    @include('_partials.dataTable-script')
@endsection