@extends('layout.admin.master')

@section('title' , 'Branches')

@section('main-breadcrumb', 'Branches')
@section('main-breadcrumb-link', route('branches.index'))

@section('sub-breadcrumb', 'Index')

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

            @can('create_branches')
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                        <a href="{{ route('branches.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Branch</a>
                    </div>
                </div>
            @endcan

        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Branch Name</th>
                        <th>Manager</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Map</th>
                        <th>Subscribers</th>
                        <th>Created at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($branches as $key => $branch)
                        <tr>
                            <td>
                                {{ ++$key }}
                            </td>
                            <td> {{$branch->name}} </td>
                            <td> {{$branch->manager->name}}</td>
                            <td> {{$branch->location}} </td>
                            <td> {{$branch->type}}</td>
                            <td>
                                @if($branch->map_url)
                                    <a href="{{ $branch->map_url }}" target="_blank" class="btn btn-sm btn-light-primary">
                                        <i class="ki-duotone ki-map fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        View Map
                                    </a>
                                @else
                                    <span class="text-muted">No map</span>
                                @endif
                            </td>
                            <td> {{$branch->subscriptions_count}}</td>
                            <td> {{date('d-M-Y' , strtotime( $branch->created_at))}} </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('edit_branches')
                                        <x-table-icon-link 
                                            :route="route('branches.edit',$branch->id)" 
                                            colorClass="primary"
                                            title="Edit"
                                            iconClasses="fa-solid fa-pen"
                                        />
                                    @endcan
                                    @can('view_branches')
                                        <x-table-icon-link 
                                            :route="route('branches.show',$branch->id)" 
                                            colorClass="success"
                                            title="View"
                                            iconClasses="fa-solid fa-eye"
                                        />
                                    @endcan
                                    @can('delete_branches')
                                        <form action="{{ route('branches.destroy' ,$branch->id )}}" method="post">
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