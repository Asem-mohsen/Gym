@extends('layout.admin.master')

@section('title' , 'Memberships')

@section('main-breadcrumb', 'Memberships')
@section('main-breadcrumb-link', route('membership.index'))

@section('sub-breadcrumb', 'Index')

@section('content')

    @include('admin.memberships.style.custom-style')

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

                @can('create_memberships')
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                            <a href="{{ route('membership.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Membership</a>
                        </div>
                    </div>
                @endcan
            </div>

            <div class="card-body pt-0">

                <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                            <th>#</th>
                            <th>Name</th>
                            <th>Period</th>
                            <th>Features</th>
                            <th>Invitation Limit</th>
                            <th>Num. Subscriptions</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($memberships as $key => $membership)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td>{{$membership->name}}</td>
                                <td>{{$membership->period}}</td>
                                <td>
                                    @if($membership->features->count() > 0)
                                        @foreach($membership->features->take(3) as $feature)
                                            <x-badge 
                                                :color="'light-primary'" 
                                                content="{{ $feature->getTranslation('name', app()->getLocale()) }}"
                                            />
                                        @endforeach
                                        @if($membership->features->count() > 3)
                                            <span class="badge badge-light-secondary">+{{ $membership->features->count() - 3 }} more</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No features</span>
                                    @endif
                                </td>
                                <td>{{ $membership->invitation_limit ?? 0 }}</td>
                                <td>{{$membership->payment_count }}</td>
                                <td>
                                    @if ($membership->subtitle)
                                        {{ \Illuminate\Support\Str::limit($membership->subtitle, 25) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $membership->price . ' EGP' }}</td>
                                <td>
                                    @if($membership->status === 1)
                                        <x-badge 
                                            :color="'success'" 
                                            content="Active"
                                        />
                                    @else
                                        <x-badge 
                                            :color="'danger'" 
                                            content="Diactivated"
                                        />
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @can('edit_memberships')
                                        <x-table-icon-link 
                                            :route="route('membership.edit',$membership->id)" 
                                            colorClass="primary"
                                            title="Edit"
                                            iconClasses="fa-solid fa-pen"
                                        />
                                        @endcan
                                        @can('view_memberships')
                                        <x-table-icon-link 
                                            :route="route('membership.show',$membership->id)" 
                                            colorClass="success"
                                            title="View"
                                            iconClasses="fa-solid fa-eye"
                                        />
                                        @endcan
                                        @can('delete_memberships')
                                        <form action="{{ route('membership.destroy' ,$membership->id )}}" method="post">
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

    <div class="card">
        <div class="pricing">
            @foreach ($memberships as $membership)
                <div class="plan {{ $maxBookings > 0 && $membership->bookings_count == $maxBookings ? 'popular' : '' }}">
                    <h2>{{$membership->name}}</h2>
                    <div class="price">{{ $membership->price . ' EGP' }}</div>
                    <div>
                        <p>{{$membership->subtitle}}</p>
                    </div>
                    <ul class="features">
                        <li><i class="fas fa-check-circle"></i>{{$membership->period}}</li>
                        @foreach($membership->features as $feature)
                            <li><i class="fas fa-check-circle"></i>{{$feature->getTranslation('name', app()->getLocale())}}</li>
                        @endforeach
                    </ul>
                    <x-table-icon-link 
                        :route="route('membership.edit',$membership->id)" 
                        colorClass="success"
                        title="Edit"
                        iconClasses="fa-solid fa-pen"
                    />
                </div>
            @endforeach
        </div>
    </div>

@endsection

@section('js')
    @include('_partials.dataTable-script')
@endsection