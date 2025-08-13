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
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua accusantium doloremque laudantium. Excepteur
                                sint occaecat cupidatat non proident sculpa .</p>
                            <p>laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure Lorem ipsum dolor sit
                                amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore
                                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                aliquip ex ea commodo consequat anim id est laborum.</p>
                            <h5>You Can Buy For Less Than A College Degree</h5>
                            <p>Dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit
                                in voluptate velit esse cillum dolore eu fugiat nulla pariatur officia deserunt mollit.
                            </p>
                        </div>
                        <div class="blog-details-pic">
                            <div class="blog-details-pic-item">
                                <img src="{{ asset('assets/user/img/blog/details/details-1.jpg')}}" alt="">
                            </div>
                            <div class="blog-details-pic-item">
                                <img src="{{ asset('assets/user/img/blog/details/details-2.jpg')}}" alt="">
                            </div>
                        </div>
                        <div class="blog-details-desc">
                            <p>Dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit
                                in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                                cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>
                        <div class="blog-details-quote">
                            <div class="quote-icon">
                                <img src="{{ asset('assets/user/img/blog/details/quote-left.png')}}" alt="">
                            </div>
                            <h5>The whole family of tiny legumes, whether red, green, yellow, or black, offers so many
                                possibilities to create an exciting lunch.</h5>
                            <span>MEIKE PETERS</span>
                        </div>
                        <div class="blog-details-more-desc">
                            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                                dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt
                                in. . Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
                            <p>laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure Lorem ipsum dolor sit
                                amet, consectetur adipisicing elit, sed eiusmod tempor incididunt laboris nisi ut
                                aliquip commodo consequat. Class aptent taciti sociosqu ad litora torquent per conubia
                                nostra, per inceptos himenaeos. Mauris vel magna ex. Integer gravida tincidunt accumsan.
                                Vestibulum nulla mauris, condimentum id felis ac, volutpat volutpat mi qui dolorem.</p>
                        </div>
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
                        <div class="blog-details-author">
                            <div class="ba-pic">
                                <img src="{{ asset('assets/user/img/blog/details/blog-profile.jpg')}}" alt="">
                            </div>
                            <div class="ba-text">
                                <h5>{{ $blogPost->user->name }}</h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                    exercitation.</p>
                                <div class="bp-social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-google-plus"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                    <a href="#"><i class="fa fa-youtube-play"></i></a>
                                </div>
                            </div>
                        </div>
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
                                                    <img src="{{ $comment->user?->avatar ?? asset('assets/user/img/blog/details/comment-1.jpg') }}" alt="">
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
                                                                    <img src="{{ $reply->user?->avatar ?? asset('assets/user/img/blog/details/comment-2.jpg') }}" alt="">
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
                                        <button type="submit">Submit</button>
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
