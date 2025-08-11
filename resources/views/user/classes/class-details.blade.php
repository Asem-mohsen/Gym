@extends('layout.user.master')

@section('title', 'Class Details')

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
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                    irure Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua accusantium doloremque
                                    laudantium. Excepteur sint occaecat cupidatat non proident sculpa.</p>
                            </div>
                            <div class="cd-single-item">
                                <h3>Trainer{{ $class->trainers->count() > 1 ? 's' : '' }}</h3>
                                <p>Dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                    labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                    ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                    reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur officia
                                    deserunt mollit.</p>
                            </div>
                        </div>
                        <div class="cd-trainer">
                            @foreach ($class->trainers as $trainer)
                                <div class="row mb-5 align-items-center">
                                    @if ($loop->iteration % 2 != 0) 
                                        <div class="col-md-6">
                                            <div class="cd-trainer-pic">
                                                <img src="{{ $trainer->getFirstMediaUrl('user_images') }}" alt="">
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
                                                <img src="{{ $trainer->getFirstMediaUrl('user_images') }}" alt="">
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
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Class Details Section End -->

    <!-- Class Timetable Section Begin -->
    <section class="class-timetable-section class-details-timetable spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="class-details-timetable_title">
                        <h5>Classes timetable</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="class-timetable details-timetable">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                    <th>Sunday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="class-time">6.00am - 8.00am</td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>WEIGHT LOOSE</h5>
                                        <span>RLefew D. Loee</span>
                                    </td>
                                    <td class="hover-dp ts-meta" data-tsmeta="fitness">
                                        <h5>Cardio</h5>
                                        <span>RLefew D. Loee</span>
                                    </td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>Yoga</h5>
                                        <span>Keaf Shen</span>
                                    </td>
                                    <td class="hover-dp ts-meta" data-tsmeta="fitness">
                                        <h5>Fitness</h5>
                                        <span>Kimberly Stone</span>
                                    </td>
                                    <td class="dark-bg blank-td"></td>
                                    <td class="hover-dp ts-meta" data-tsmeta="motivation">
                                        <h5>Boxing</h5>
                                        <span>Rachel Adam</span>
                                    </td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>Body Building</h5>
                                        <span>Robert Cage</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="class-time">10.00am - 12.00am</td>
                                    <td class="blank-td"></td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="fitness">
                                        <h5>Fitness</h5>
                                        <span>Kimberly Stone</span>
                                    </td>
                                    <td class="hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>WEIGHT LOOSE</h5>
                                        <span>RLefew D. Loee</span>
                                    </td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="motivation">
                                        <h5>Cardio</h5>
                                        <span>RLefew D. Loee</span>
                                    </td>
                                    <td class="hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>Body Building</h5>
                                        <span>Robert Cage</span>
                                    </td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="motivation">
                                        <h5>Karate</h5>
                                        <span>Donald Grey</span>
                                    </td>
                                    <td class="blank-td"></td>
                                </tr>
                                <tr>
                                    <td class="class-time">5.00pm - 7.00pm</td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="fitness">
                                        <h5>Boxing</h5>
                                        <span>Rachel Adam</span>
                                    </td>
                                    <td class="hover-dp ts-meta" data-tsmeta="motivation">
                                        <h5>Karate</h5>
                                        <span>Donald Grey</span>
                                    </td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>Body Building</h5>
                                        <span>Robert Cage</span>
                                    </td>
                                    <td class="blank-td"></td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>Yoga</h5>
                                        <span>Keaf Shen</span>
                                    </td>
                                    <td class="hover-dp ts-meta" data-tsmeta="motivation">
                                        <h5>Cardio</h5>
                                        <span>RLefew D. Loee</span>
                                    </td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="fitness">
                                        <h5>Fitness</h5>
                                        <span>Kimberly Stone</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="class-time">7.00pm - 9.00pm</td>
                                    <td class="hover-dp ts-meta" data-tsmeta="motivation">
                                        <h5>Cardio</h5>
                                        <span>RLefew D. Loee</span>
                                    </td>
                                    <td class="dark-bg blank-td"></td>
                                    <td class="hover-dp ts-meta" data-tsmeta="fitness">
                                        <h5>Boxing</h5>
                                        <span>Rachel Adam</span>
                                    </td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>Yoga</h5>
                                        <span>Keaf Shen</span>
                                    </td>
                                    <td class="hover-dp ts-meta" data-tsmeta="motivation">
                                        <h5>Karate</h5>
                                        <span>Donald Grey</span>
                                    </td>
                                    <td class="dark-bg hover-dp ts-meta" data-tsmeta="fitness">
                                        <h5>Boxing</h5>
                                        <span>Rachel Adam</span>
                                    </td>
                                    <td class="hover-dp ts-meta" data-tsmeta="workout">
                                        <h5>WEIGHT LOOSE</h5>
                                        <span>RLefew D. Loee</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Class Timetable Section End -->

@endsection