@extends('layout.admin.master')

@section('title', 'Users')

@section('main-breadcrumb', 'Users')
@section('main-breadcrumb-link', route('users.index'))

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
                        <input type="text" id="search-input" class="form-control form-control-solid w-250px ps-12" placeholder="Search users..." value="{{ request('search') }}" />
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
                    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add User</a>
                </div>
            </div>

        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Address</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        <img src="{{ $user->user_image }}" class="avatar avatar-sm me-3" alt="{{$user->name}}">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{$user->name}}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->phone}}</td>
                            <td> 
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary text-white">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($user->address)
                                    {{ \Illuminate\Support\Str::limit($user->address, 25) }}
                                @else
                                    No data
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d F Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-table-icon-link 
                                        :route="route('users.edit',$user->id)" 
                                        colorClass="primary"
                                        title="Edit"
                                        iconClasses="fa-solid fa-pen"
                                    />
                                    <x-table-icon-link 
                                        :route="route('users.show',$user->id)" 
                                        colorClass="success"
                                        title="View"
                                        iconClasses="fa-solid fa-eye"
                                    />
                                    @if(!$user->has_set_password)
                                        <form action="{{ route('users.resend-onboarding-email', $user->id) }}" method="post" style="display: inline;">
                                            @csrf
                                            <x-icon-button
                                                colorClass="warning"
                                                title="Resend Onboarding Email"
                                                iconClasses="fa-solid fa-envelope"
                                                onclick="return confirm('Are you sure you want to resend the onboarding email to {{ $user->name }}?')"
                                            />
                                        </form>
                                    @endif
                                    <form action="{{ route('users.destroy' ,$user->id )}}" method="post">
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
                <nav aria-label="Users pagination">
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
    @include('_partials.dataTable-script')
@endsection