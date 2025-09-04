@extends('layout.user.master')

@section('title', 'Class Timetable')

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Timetable</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <a href="{{ route('user.classes.index' , ['siteSetting' => $siteSetting->slug]) }}">Classes</a>
                            <span>Timetable</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Class Timetable Section Begin -->
    @if ($timetableData)
        <x-user.class-timetable :timetableData="$timetableData" :classTypes="$classTypes" :siteSetting="$siteSetting" />
    @else
        <div class="bg-gym-primary" style="background-color: #151515;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info text-center mt-5 mb-5">No classes available.</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Class Timetable Section End -->

@endsection
