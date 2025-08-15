@extends('layout.user.master')

@section('title', 'Blog Details')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/user/css/blog-comments.css') }}">
@endsection

@section('content')

    <!-- Blog Details Hero Section Begin -->
    <section class="blog-details-hero set-bg" data-setbg="{{ $blogPost->getFirstMediaUrl('blog_post_images')}}">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 p-0 m-auto">
                    <div class="bh-text">
                        <h3>{{$blogPost->title}}</h3>
                        <ul>
                            <li>{{$blogPost->user->name}}</li>
                            <li>{{$blogPost->published_at->format('M, d, Y')}}</li>
                            <li>{{$blogPost->comments()->count()}} Comments</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Hero Section End -->

    <!-- Blog Details Section Begin -->
    <section class="blog-details-section spad">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8 p-0 m-auto">
                    <div class="blog-details-text" data-blog-post-id="{{ $blogPost->id }}">
                        <div class="blog-details-title">
                            <p>
                                {!! $blogPost->content !!}
                            </p>
                        </div>
                        <div class="blog-details-pic">
                            @foreach($blogPost->getMedia('blog_post_other_images') as $media)
                                <div class="blog-details-pic-item">
                                    <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="blog-details-desc">
                            <p>
                                {!! $blogPost->description !!}
                            </p>
                        </div>
                        @if($blogPost->quote_author_name)
                            <div class="blog-details-quote">
                                <div class="quote-icon">
                                    <img src="{{ asset('assets/user/img/blog/details/quote-left.png')}}" alt="">
                                </div>
                                <h5>
                                    {!! $blogPost->quote_author_title !!}
                                </h5>
                                <span>
                                    {!! $blogPost->quote_author_name !!}
                                </span>
                            </div>
                        @endif
                        <div class="blog-details-tag-share">
                            <div class="tags">
                                @foreach ($blogPost->tags as $tag)
                                    <a href="#">{{$tag->name}}</a>
                                @endforeach
                            </div>
                        <div class="share">
                                <span>Share</span>
                                <button type="button" class="share-btn" data-platform="facebook">
                                    <i class="fa fa-facebook"></i> <span class="share-count">{{ $shareStatistics['facebook'] ?? 0 }}</span>
                                </button>
                                <button type="button" class="share-btn" data-platform="twitter">
                                    <i class="fa fa-twitter"></i> <span class="share-count">{{ $shareStatistics['twitter'] ?? 0 }}</span>
                                </button>
                                <button type="button" class="share-btn" data-platform="email">
                                    <i class="fa fa-envelope"></i> <span class="share-count">{{ $shareStatistics['email'] ?? 0 }}</span>
                                </button>
                            </div>
                        </div>
                        @if($blogPost->author_comment)
                            <div class="blog-details-author">
                                <div class="ba-pic">
                                    <img src="{{ $blogPost->user->user_image ?? asset('assets/user/img/blog/details/blog-profile.jpg')}}" alt="">
                                </div>
                                <div class="ba-text">
                                    <h5>{{ $blogPost->user->name }}</h5>
                                    <p>
                                        {!! $blogPost->author_comment !!}
                                    </p>
                                    <div class="bp-social">
                                        <a href="{{ $blogPost->user->facebook_url }}"><i class="fa fa-facebook"></i></a>
                                        <a href="{{ $blogPost->user->twitter_url }}"><i class="fa fa-twitter"></i></a>
                                        <a href="{{ $blogPost->user->instagram_url }}"><i class="fa fa-instagram"></i></a>
                                        <a href="{{ $blogPost->user->youtube_url }}"><i class="fa fa-youtube-play"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="comment-option">
                                    <h5 class="co-title">Comments ({{ $comments->total() }})</h5>
                                    <div id="comments-container">
                                        @foreach($comments as $comment)
                                            <div class="co-item" data-comment-id="{{ $comment->id }}">
                                                <div class="co-widget">
                                                    <button type="button" class="like-btn {{ auth()->check() && $comment->isLikedBy(auth()->user()) ? 'liked' : '' }}" data-comment-id="{{ $comment->id }}">
                                                        <i class="fa fa-heart{{ auth()->check() && $comment->isLikedBy(auth()->user()) ? '' : '-o' }}"></i> 
                                                        <span class="like-count">{{ $comment->likes_count ?? 0 }}</span>
                                                    </button>
                                                    <button class="reply-btn" data-comment-id="{{ $comment->id }}">
                                                        <i class="fa fa-reply"></i>
                                                    </button>
                                                </div>
                                                <div class="co-pic">
                                                    <img src="{{ $comment->user?->user_image ?? asset('assets/user/img/blog/details/comment-1.jpg') }}" alt="">
                                                    <h5>{{ $comment->user?->name ?? 'Anonymous' }}</h5>
                                                </div>
                                                <div class="co-text">
                                                    <p>{{ $comment->content }}</p>
                                                    <small>{{ $comment->created_at->format('M d, Y g:i A') }}</small>
                                                </div>
                                                <div class="reply-form" data-comment-id="{{ $comment->id }}" style="display: none;">
                                                    <form data-comment-id="{{ $comment->id }}">
                                                        <textarea name="content" placeholder="Write a reply..." required></textarea>
                                                        <button type="submit">Reply</button>
                                                    </form>
                                                </div>
                                                
                                                @if($comment->children->count() > 0)
                                                    <div class="reply-comment mt-5">
                                                        @foreach($comment->children as $reply)
                                                            <div class="co-item" data-comment-id="{{ $reply->id }}">
                                                                <div class="co-widget">
                                                                    <button type="button" class="like-btn {{ auth()->check() && $reply->isLikedBy(auth()->user()) ? 'liked' : '' }}" data-comment-id="{{ $reply->id }}">
                                                                        <i class="fa fa-heart{{ auth()->check() && $reply->isLikedBy(auth()->user()) ? '' : '-o' }}"></i> 
                                                                        <span class="like-count">{{ $reply->likes_count ?? 0 }}</span>
                                                                    </button>
                                                                </div>
                                                                <div class="co-pic">
                                                                    <img src="{{ $reply->user?->user_image ?? asset('assets/user/img/blog/details/comment-2.jpg') }}" alt="">
                                                                    <h5>{{ $reply->user?->name ?? 'Anonymous' }}</h5>
                                                                </div>
                                                                <div class="co-text">
                                                                    <p>{{ $reply->content }}</p>
                                                                    <small>{{ $reply->created_at->format('M d, Y g:i A') }}</small>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($comments->hasPages())
                                        <div class="pagination-wrapper">
                                            {{ $comments->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="leave-comment">
                                    <h5>Leave a comment</h5>
                                    <form id="comment-form">
                                        <textarea name="content" placeholder="Comment" required></textarea>
                                        @if(auth()->check())
                                            <button type="submit">Submit</button>
                                        @else
                                            <a class="btn" href="{{ route('auth.login.index') }}">Login to Comment</a>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->

@endsection

@section('Js')
<script>
    window.currentUser = @json(auth()->user());
    window.gymSlug = '{{ request()->route("siteSetting")->slug }}';
    window.shareStatistics = @json($shareStatistics);
    window.sharingUrls = @json($sharingUrls);
</script>
<script src="{{ asset('assets/user/js/blog-comments.js') }}"></script>
@endsection
