@extends('layout.admin.master')
@section('title' , 'Gallery Management')
@section('page-title', 'Gallery Management')

@section('main-breadcrumb', 'Gallery Management')
@section('main-breadcrumb-link', route('galleries.index'))

@section('sub-breadcrumb', 'Index')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">

        {{-- @if($stats)
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fa fa-images"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Galleries</span>
                            <span class="info-box-number">{{ $stats['total_galleries'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fa fa-image"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Images</span>
                            <span class="info-box-number">{{ $stats['total_images'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fa fa-hdd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Size</span>
                            <span class="info-box-number">{{ $stats['total_size_mb'] }} MB</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}

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
                    <a href="{{ route('galleries.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add New Gallery</a>
                </div>
            </div>

        </div>
        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Preview</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Images Count</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($galleries as $key => $gallery)
                        <tr>
                            <td>
                                {{ ++$key }}
                            </td>
                            <td>
                                @php
                                    $firstImage = $gallery->getMedia('gallery_images')->first();
                                @endphp
                                @if($firstImage)
                                    <img src="{{ $firstImage->getUrl() }}" 
                                            alt="{{ $gallery->title }}" 
                                            class="img-thumbnail" 
                                            style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 60px; height: 60px;">
                                        <i class="fa fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $gallery->title }}</strong>
                            </td>
                            <td>
                                {{ Str::limit($gallery->description, 100) }}
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $gallery->getMedia('gallery_images')->count() }}</span>
                            </td>
                            <td>
                                @if($gallery->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $gallery->sort_order }}</td>
                            <td>{{ $gallery->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-table-icon-link 
                                        :route="route('galleries.edit',$gallery->id)" 
                                        colorClass="primary"
                                        title="Edit"
                                        iconClasses="fa-solid fa-pen"
                                    />
                                    <form action="{{ route('galleries.destroy' ,$gallery->id )}}" method="post">
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
        </div>
    </div>
</div>
@endsection