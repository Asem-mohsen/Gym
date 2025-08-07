@extends('layout.admin.master')

@section('title' , 'Blog')

@section('page-title', 'Blog Posts')

@section('main-breadcrumb', 'Blog Posts')
@section('main-breadcrumb-link', route('blog-posts.index'))

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
                    <a href="{{ route('blog-posts.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add New Post</a>
                </div>
            </div>

        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Category</th>
                        <th>Tags</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($blogPosts as $key => $blogPost)
                        <tr>
                            <td>
                                {{ ++$key }}
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        <img src="{{$blogPost->getFirstMediaUrl('blog_post_images')}}" class="avatar avatar-sm me-3" alt="{{$blogPost->title}}">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{$blogPost->title}}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ \Illuminate\Support\Str::words(strip_tags($blogPost->content), 10, '...') }}</td>
                            <td>
                                {!! $blogPost->categories->pluck('name')->map(fn($name) => '<span class="badge badge-success">' . e($name) . '</span>')->implode(' ') !!}
                            </td>
                            <td>
                                {!! $blogPost->tags->pluck('name')->map(fn($name) => '<span class="badge badge-primary">' . e($name) . '</span>')->implode(' ') !!}
                            </td>
                            <td>
                                {{ $blogPost->status }}
                            </td>
                            <td>
                                {{ $blogPost->created_at->format('d F Y') }}
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-table-icon-link 
                                        :route="route('blog-posts.edit',$blogPost->id)" 
                                        colorClass="primary"
                                        title="Edit"
                                        iconClasses="fa-solid fa-pen"
                                    />
                                    <x-table-icon-link 
                                        :route="route('blog-posts.show',$blogPost->id)" 
                                        colorClass="success"
                                        title="View"
                                        iconClasses="fa-solid fa-eye"
                                    />
                                    <form action="{{ route('blog-posts.destroy' ,$blogPost->id )}}" method="post">
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