@extends('layout.user.master')

@section('title', 'Class Details')

@section('css')
    @include('user.classes.assets.style')
@endsection

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Classes detail</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug ]) }}">Home</a>
                            <a href="{{ route('user.classes.index' , ['siteSetting' => $siteSetting->slug ]) }}">Classes</a>
                            <span>{{ $class->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Class Details Section Begin -->
    <section class="class-details-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="class-details-text">
                        <div class="cd-pic">
                            <img src="{{ $class->getFirstMediaUrl('class_images') }}" alt="{{ $class->name }}">
                        </div>
                        <div class="cd-text">
                            <div class="cd-single-item">
                                <h3>{{ $class->name }}</h3>
                                <p>
                                    {!! $class->description !!}
                                </p>
                            </div>
                            
                            <!-- Class Details Section -->
                            <div class="cd-single-item">
                                <h3>Class Details</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="class-detail-item">
                                            <h5><i class="fa fa-clock-o"></i> Schedules</h5>
                                            @if($class->schedules->count() > 0)
                                                <ul class="schedule-list">
                                                    @foreach($class->schedules as $schedule)
                                                        <li>
                                                            <strong>{{ ucfirst($schedule->day) }}:</strong> 
                                                            {{ date('g:i A', strtotime($schedule->start_time)) }} - {{ date('g:i A', strtotime($schedule->end_time)) }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>No schedules available</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="class-detail-item">
                                            <h5><i class="fa fa-money"></i> Pricing</h5>
                                            @if($class->pricings->count() > 0)
                                                <ul class="pricing-list">
                                                    @foreach($class->pricings as $pricing)
                                                        <li>
                                                            <strong>${{ number_format($pricing->price, 2) }}</strong> 
                                                            <span>{{ $pricing->duration }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>Pricing not available</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cd-single-item">
                                <h3>Trainer{{ $class->trainers->count() > 1 ? 's' : '' }}</h3>
                                <p>
                                    Our certified trainer{{ $class->trainers->count() > 1 ? 's are' : ' is' }} dedicated to helping members
                                    achieve their fitness goals through expert guidance, motivation, and safe training practices.
                                    They bring experience, professionalism, and a passion for fitness to every class, ensuring that
                                    participants of all levels can train effectively and enjoyably.
                                </p>
                            </div>
                        </div>
                        <div class="cd-trainer">
                            @foreach ($class->trainers as $trainer)
                                <div class="row mb-5 align-items-center">
                                    @if ($loop->iteration % 2 != 0) 
                                        <div class="col-md-6">
                                            <div class="cd-trainer-pic">
                                                <img src="{{ $trainer->user_image }}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            @include('_partials.trainer_text', ['trainer' => $trainer])
                                        </div>
                                    @else
                                        <div class="col-md-6">
                                            @include('_partials.trainer_text', ['trainer' => $trainer])
                                        </div>
                                        <div class="col-md-6">
                                            <div class="cd-trainer-pic">
                                                <img src="{{ $trainer->user_image }}" alt="">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8">
                    <div class="sidebar-option">
                        <div class="so-categories">
                            <h5 class="title">Categories</h5>
                            <ul>
                                @foreach ($categories as $category)
                                    <li><a href="#">{{ $category->name }} <span>{{ $category->blog_posts_count }}</span></a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="so-latest">
                            <h5 class="title">Latest posts</h5>
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
                        <div class="so-banner set-bg" data-setbg="{{ asset('assets/user/img/sidebar-banner.jpg') }}">
                            <h5>{{$class->name}}</h5>
                        </div>

                        <!-- Booking Section -->
                        <div class="cd-single-item booking-form-container">
                            <h3>Book This Class</h3>
                            <form action="{{ route('user.classes.book', ['siteSetting' => $siteSetting->slug, 'class' => $class->id]) }}" method="POST" class="booking-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="branch_id">Select Branch *</label>
                                            <select name="branch_id" id="branch_id" class="form-control" required>
                                                <option value="">Choose a branch</option>
                                                @foreach($class->branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="schedule_id">Select Schedule *</label>
                                            <select name="schedule_id" id="schedule_id" class="form-control" required>
                                                <option value="">Choose a schedule</option>
                                                @foreach($class->schedules as $schedule)
                                                    <option value="{{ $schedule->id }}">
                                                        {{ ucfirst($schedule->day) }} - {{ date('g:i A', strtotime($schedule->start_time)) }} to {{ date('g:i A', strtotime($schedule->end_time)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                @auth
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                                <i class="fa fa-calendar-check-o"></i> Book Class
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="{{ route('auth.login.index', ['siteSetting' => $siteSetting->slug]) }}" class="btn btn-primary btn-lg btn-block">
                                                <i class="fa fa-user-plus"></i> Login to Book Class
                                            </a>
                                        </div>
                                    </div>
                                @endauth
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Class Details Section End -->

    <!-- Class Timetable Section Begin -->
    <x-user.class-timetable 
        :timetableData="$timetableData" 
        :classTypes="$classTypes" 
        :siteSetting="$siteSetting" 
        variant="compact"
    />
    <!-- Class Timetable Section End -->

@endsection