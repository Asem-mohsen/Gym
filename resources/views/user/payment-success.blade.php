@extends('layout.user.master')

@section('title', 'Payment Redirect')

@section('content')

    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Payment Success</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <span>Payment Success</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Section Begin -->
    <section class="success-section spad" style="background: #151515;">
        <div class="container mx-auto my-10 text-center">
            <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg mx-auto">
                <h1 class="text-2xl font-bold text-green-600 mb-4">
                    🎉 Thank You!
                </h1>

                <p class="text-gray-700 mb-6">
                    Your payment has been processed successfully. 
                    Email receipt has been sent to your email address.
                </p>

                <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Back to Home
                </a>
            </div>
        </div>
    </section>
    <!-- Success Section End -->

@endsection
