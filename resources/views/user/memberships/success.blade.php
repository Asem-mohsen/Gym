@extends('layout.user.master')

@section('title', 'Enrollment Successful')

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Enrollment Successful!</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <a href="{{ route('user.memberships.index' , ['siteSetting' => $siteSetting->slug]) }}">Memberships</a>
                            <span>Success</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Success Section Begin -->
    <section class="pricing-section spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="success-content">
                        <div class="success-icon mb-4">
                            <i class="fa fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        
                        <h3 class="text-success mb-4">Welcome to the Family!</h3>
                        
                        <p class="lead mb-4">
                            Your membership enrollment has been completed successfully. 
                            You're now part of our fitness community!
                        </p>
                        
                        <div class="enrollment-details mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Enrollment Details</h5>
                                    <p class="card-text">
                                        <strong>Payment ID:</strong> {{ $paymentIntentId ?? 'N/A' }}<br>
                                        <strong>Date:</strong> {{ now()->format('F j, Y') }}<br>
                                        <strong>Status:</strong> <span class="badge bg-success">Active</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="next-steps mb-4">
                            <h5>What's Next?</h5>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-arrow-right text-primary"></i> Check your email for welcome details</li>
                                <li><i class="fa fa-arrow-right text-primary"></i> Download our mobile app to track your progress</li>
                                <li><i class="fa fa-arrow-right text-primary"></i> Schedule your first training session</li>
                                <li><i class="fa fa-arrow-right text-primary"></i> Visit our facilities and meet your trainers</li>
                            </ul>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}" class="primary-btn me-3">
                                Go to Home
                            </a>
                            <a href="{{ route('user.contact' , ['siteSetting' => $siteSetting->slug]) }}" class="btn btn-outline-primary">
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Success Section End -->

@endsection
