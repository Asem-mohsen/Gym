@extends('layout.user.master')

@section('title', 'Check In - ' . $siteSetting->gym_name)

@section('css')
    @include('user.checkin.assets.styles.self-style')
@endsection

@section('content')

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Check In</h2>
                    <div class="bt-option">
                        <a href="{{route('user.home', ['siteSetting' => $siteSetting->slug])}}">Home</a>
                        <span>Check In</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Check-in Section Begin -->
<section class="checkin-section spad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title">
                    <h2>Welcome to {{ $siteSetting->gym_name }}</h2>
                    <p>Quick and secure check-in process</p>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="checkin-welcome">
                            <div class="welcome-card text-white">
                                <div class="welcome-icon">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                                <h4>Welcome, {{ $user->name }}!</h4>
                                <p>You're checking in to {{ $siteSetting->gym_name }}</p>
                            </div>

                            @if(!$validation['valid'])
                                <div class="alert alert-danger">
                                    <i class="fa fa-times-circle me-2"></i>
                                    <strong>Check-in Failed:</strong> {{ $validation['message'] }}
                                </div>
                            @else
                                <div class="checkin-status">
                                    <div class="status-info">
                                        <i class="fa fa-info-circle me-2"></i>
                                        <strong>Status:</strong> {{ $validation['message'] }}
                                    </div>
                                </div>

                                <div class="checkin-instructions">
                                    <h6><i class="fa fa-lightbulb me-2"></i>How to Check In:</h6>
                                    <p class="text-white">📱 Scan the QR code displayed at the gym entrance using your phone camera or the gym app to check in quickly and securely.</p>
                                </div>

                                <form action="{{ route('user.checkin.self.process', ['siteSetting' => $siteSetting->slug]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="primary-btn btn-block">
                                        <i class="fa fa-sign-in-alt me-2"></i>
                                        Check In Now
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="qr-code-section">
                            <div class="qr-card text-white">
                                <div class="qr-icon">
                                    <i class="fa fa-qrcode"></i>
                                </div>
                                <h6 >Gym QR Code</h6>
                                <p>Scan this QR code at the gym entrance to check in quickly</p>
                                
                                <div class="qr-display">
                                    <div class="qr-placeholder">
                                        <i class="fa fa-qrcode"></i>
                                    </div>
                                    <small>QR Code for {{ $siteSetting->gym_name }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5 text-white">
                    <div class="col-lg-6">
                        <div class="quick-actions">
                            <h6 class="mb-4"><i class="fa fa-history mr-2"></i>Quick Actions</h6>
                            <div class="action-buttons">
                                @if($checkinSettings->enable_gate_scan)
                                    <a href="{{ route('user.checkin.personal-qr', $siteSetting->slug) }}" class="secondary-btn">
                                        <i class="fa fa-qrcode me-2"></i>
                                        View My QR Code
                                    </a>
                                @endif
                                <a href="{{ route('user.checkin.history', $siteSetting->slug) }}" class="secondary-btn">
                                    <i class="fa fa-history me-2"></i>
                                    Check-in History
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="checkin-info">
                            <h6 class="mb-3"><i class="fa fa-info-circle mr-2"></i>Check-in Info</h6>
                            <ul class="info-list">
                                <li><i class="fa fa-eye mr-2"></i>Last visit: {{ $user->last_visit_at ? $user->last_visit_at->diffForHumans() : 'Never' }}</li>
                                <li><i class="fa fa-calendar mr-2"></i>Today: {{ now()->format('M d, Y') }}</li>
                                <li><i class="fa fa-user mr-2"></i>Member since: {{ $user->created_at->format('M Y') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Check-in Section End -->

@endsection

@section('Js')
    {{-- @include('user.checkin.assets.scripts.personal-qr-script') --}}
@endsection