@extends('layout.admin.master')

@section('title' , 'Features')

@section('main-breadcrumb', 'Features')
@section('main-breadcrumb-link', route('features.index'))

@section('sub-breadcrumb', 'Index')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

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

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                        <a href="{{ route('features.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add Feature</a>
                    </div>
                </div>

            </div>

            <div class="card-body pt-0">

                <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($features as $key => $feature)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td>{{$feature->name}}</td>
                                <td>
                                    @if ($feature->description)
                                        {{ \Illuminate\Support\Str::limit($feature->description, 50) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $feature->order }}</td>
                                <td>
                                    @if($feature->status == 1)
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
                                <td>
                                    <div class="d-flex gap-1">
                                        <x-table-icon-link 
                                            :route="route('features.edit',$feature->id)" 
                                            colorClass="primary"
                                            title="Edit"
                                            iconClasses="fa-solid fa-pen"
                                        />
                                        <x-table-icon-link 
                                            :route="route('features.show',$feature->id)" 
                                            colorClass="success"
                                            title="View"
                                            iconClasses="fa-solid fa-eye"
                                        />
                                        <x-table-icon-link 
                                            :route="route('features.destroy',$feature->id)" 
                                            colorClass="danger"
                                            title="Delete"
                                            iconClasses="fa-solid fa-trash"
                                            :isDelete="true"
                                        />
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