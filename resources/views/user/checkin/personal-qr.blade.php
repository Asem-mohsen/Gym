@extends('layout.user.master')

@section('title', 'My QR Code - ' . $siteSetting->gym_name)

@section('css')
    @include('user.checkin.assets.styles.personal-qr-style')
@endsection

@section('content')

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>My QR Code</h2>
                    <div class="bt-option">
                        <a href="{{route('user.home', ['siteSetting' => $siteSetting->slug])}}">Home</a>
                        <a href="{{route('user.checkin.self', ['siteSetting' => $siteSetting->slug])}}">Check In</a>
                        <span>My QR Code</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Personal QR Section Begin -->
<section class="personal-qr-section spad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title">
                    <h2>Your Personal QR Code</h2>
                    <p>Show this QR code to staff for quick check-in at {{ $siteSetting->gym_name }}</p>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="user-info-card text-white">
                            <h5>{{ $user->name }}</h5>
                            <p>{{ $siteSetting->gym_name }} Member</p>
                        </div>

                        <div class="qr-instructions">
                            <h6 class="text-white">How to use your QR code:</h6>
                            <p>Show your personal QR code to our staff at the entrance for check-in. Staff will scan your code to verify your membership.</p>
                        </div>

                        <div class="qr-actions">
                            <button class="primary-btn" onclick="downloadQR()">
                                <i class="fa fa-download me-2"></i>
                                Download QR Code
                            </button>
                            <button class="secondary-btn" onclick="printQR()">
                                <i class="fa fa-print me-2"></i>
                                Print QR Code
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="qr-display-card">
                            <div class="qr-header">
                                <h6>Your Personal QR Code</h6>
                                <p>Scan this code for check-in</p>
                            </div>
                            
                            <div class="qr-code-container">
                                <div id="personal-qr-code"></div>
                            </div>

                            <div class="qr-info">
                                <div class="info-item">
                                    <span class="label">Member:</span>
                                    <span class="value">{{ $user->name }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Gym:</span>
                                    <span class="value">{{ $siteSetting->gym_name }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Generated:</span>
                                    <span class="value">{{ now()->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-lg-6">
                        <div class="tips-card">
                            <h6>Tips for using your QR code</h6>
                            <ul class="tips-list">
                                <li><i class="fa fa-check mr-2"></i>Keep it on your phone for easy access</li>
                                <li><i class="fa fa-check mr-2"></i>Print a copy as backup</li>
                                <li><i class="fa fa-check mr-2"></i>Show it to staff for quick check-in</li>
                                <li><i class="fa fa-check mr-2"></i>QR code is secure and encrypted</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="security-card">
                            <h6>Security Features</h6>
                            <ul class="security-list">
                                <li><i class="fa fa-lock mr-2"></i>Encrypted with your user data</li>
                                <li><i class="fa fa-eye mr-2"></i>Valid for 24 hours</li>
                                <li><i class="fa fa-shield mr-2"></i>Linked to your membership</li>
                                <li><i class="fa fa-history mr-2"></i>Tracked check-in history</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="action-buttons mt-5">
                    <a href="{{ route('user.checkin.self', $siteSetting->slug) }}" class="secondary-btn">
                        <i class="fa fa-arrow-left me-2"></i>
                        Back to Check-in
                    </a>
                    <a href="{{ route('user.checkin.history', $siteSetting->slug) }}" class="primary-btn">
                        <i class="fa fa-history me-2"></i>
                        View History
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Personal QR Section End -->

@endsection

@section('Js')
    @include('user.checkin.assets.scripts.personal-qr-script')
@endsection
