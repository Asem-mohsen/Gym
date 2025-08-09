@extends('layout.user.master')

@section('title', 'Gallery')

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Gallery</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <span>Gallery</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Gallery Section Begin -->
    <div class="gallery-section gallery-page">
        <div class="gallery">
            <div class="grid-sizer"></div>
            @foreach ($galleries as $gallery)
                @foreach ($gallery['media'] as $index => $media)
                    @php
                        $isGridWide = in_array($index, [0, 5, 6]);
                    @endphp
                    <div class="gs-item {{ $isGridWide ? 'grid-wide' : '' }} set-bg" data-setbg="{{ $media['original_url'] }}">
                        <a href="{{ $media['original_url'] }}" class="thumb-icon image-popup">
                            <i class="fa fa-picture-o"></i>
                        </a>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
    <!-- Gallery Section End -->

@endsection