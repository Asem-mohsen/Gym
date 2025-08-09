@extends('layout.user.master')

@section('title', 'Blog')

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Our Blog</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <span>Blog</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Blog Section Begin -->
    <section class="blog-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 p-0">
                    @foreach ($blogPosts as $blogPost)
                        <div class="blog-item">
                            <div class="bi-pic">
                                <img src="{{ $blogPost->getFirstMediaUrl('blog_post_images') }}" alt="">
                            </div>
                            <div class="bi-text">
                                <h5><a href="{{ route('user.blog.show', ['siteSetting' => $siteSetting->slug, 'blogPost' => $blogPost->id]) }}">{{ $blogPost->title }}</a></h5>
                                <ul>
                                    <li>by {{ $blogPost->user->name }}</li>
                                    <li>{{ $blogPost->published_at->format('d F Y') }}</li>
                                </ul>
                                <p>{{ $blogPost->excerpt }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="blog-pagination">
                        <a href="#">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#">Next</a>
                    </div>

                </div>
                <div class="col-lg-4 col-md-8 p-0">
                    <div class="sidebar-option">
                        <div class="so-categories">
                            <h5 class="title">Categories</h5>
                            <ul>
                                @foreach ($categories as $category)
                                    <li><a href="#">{{ $category->name }} <span>{{ $category->blogPosts->count() }}</span></a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="so-latest">
                            <h5 class="title">Feature posts</h5>
                            @foreach ($blogPosts as $index => $blogPost)
                                @if($index === 0)
                                    <div class="latest-large set-bg" data-setbg="{{ $blogPost->getFirstMediaUrl('blog_post_images') }}">
                                        <div class="ll-text">
                                            <h5><a href="{{ route('user.blog.show', ['siteSetting' => $siteSetting->slug, 'blogPost' => $blogPost->id]) }}">{{ $blogPost->title }}</a></h5>
                                            <ul>
                                                <li>{{ $blogPost->published_at->format('d F Y') }}</li>
                                                <li>{{ $blogPost->comments->count() }} Comment</li>
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <div class="latest-item">
                                        <div class="li-pic">
                                            <img src="{{ $blogPost->getFirstMediaUrl('blog_post_images') }}" alt="">
                                        </div>
                                        <div class="li-text">
                                            <h6><a href="{{ route('user.blog.show', ['siteSetting' => $siteSetting->slug, 'blogPost' => $blogPost->id]) }}">{{ $blogPost->title }}</a></h6>
                                            <span class="li-time">{{ $blogPost->published_at->format('d F Y') }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="so-tags">
                            <h5 class="title">Popular tags</h5>
                            @foreach ($tags as $tag)
                                <a href="#">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->
@endsection