@extends('layout.admin.master')

@section('title' , 'Blog Post')

@section('main-breadcrumb', 'Blog Posts')
@section('main-breadcrumb-link', route('blog-posts.index'))

@section('sub-breadcrumb','Show Blog Post')

@section('content')
    <div class="container-fluid py-4">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body" id="primaryhome">
                            <h1 class="text-center">{{ $blogPost->title }}</h1>
                            <div class="row">
                                <div class="col-md-12">
                                    <img src="{{ $blogPost->getFirstMediaUrl('blog_post_images') }}" alt="{{ $blogPost->title }}" class="img-fluid mt-5" style="width: 100%; height: 80%;">
                                    <br>
                                    <h5 class="mt-4">{{ $blogPost->content }}</h5>
                                </div>
                            </div>

                            <div class="card-footer">
                                @can('edit_blog_posts')
                                    @if($blogPost->status == 'published')
                                        <form action="{{ route('blog-posts.update', $blogPost->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="draft">
                                            <button type="submit" class="btn bg-danger text-white">Unpublish</button>
                                        </form>
                                    @else
                                        <form action="{{ route('blog-posts.update', $blogPost->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="published">
                                            <input type="hidden" name="published_at" value="{{ now() }}">
                                            <button type="submit" class="btn bg-success">Publish</button>
                                        </form>
                                    @endif
                                @endcan
                                @can('view_blog_posts')
                                    <a href="{{ route('blog-posts.index') }}" class="btn btn-dark">Back</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
