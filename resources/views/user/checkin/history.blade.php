@extends('layout.user.master')

@section('title', 'Check-in History - ' . $siteSetting->gym_name)

@section('css')
    @include('user.checkin.assets.styles.history-style')
@endsection

@section('content')

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Check-in History</h2>
                    <div class="bt-option">
                        <a href="{{route('user.home', ['siteSetting' => $siteSetting->slug])}}">Home</a>
                        <a href="{{route('user.checkin.self', ['siteSetting' => $siteSetting->slug])}}">Check In</a>
                        <span>Check-in History</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- History Section Begin -->
<section class="history-section spad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title">
                    <h2>Check-in History</h2>
                    <p>Your check-in activity at {{ $siteSetting->gym_name }}</p>
                </div>

                <div class="row mb-5">
                    <div class="col-lg-6">
                        <div class="user-info-card text-white">
                            <h5>{{ $user->name }}</h5>
                            <p>{{ $siteSetting->gym_name }} Member</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number">{{ $history['total_checkins'] }}</div>
                                    <div class="stat-label">Total Check-ins</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number">{{ $history['last_checkin'] ? $history['last_checkin']->created_at->format('M d') : 'Never' }}</div>
                                    <div class="stat-label">Last Visit</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($history['checkins']->count() > 0)
                    <div class="history-table-wrapper">
                        <div class="table-responsive">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Type</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history['checkins'] as $checkin)
                                        <tr>
                                            <td>
                                                <div class="checkin-time">
                                                    <div class="date">{{ $checkin->created_at->format('M d, Y') }}</div>
                                                    <div class="time">{{ $checkin->created_at->format('h:i A') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="checkin-type {{ $checkin->checkin_type === 'self_scan' ? 'self-scan' : 'gate-scan' }}">
                                                    <i class="fa fa-{{ $checkin->checkin_type === 'self_scan' ? 'mobile' : 'camera' }} me-1"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $checkin->checkin_type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($checkin->branch)
                                                    <span class="branch-name">{{ $checkin->branch->name }}</span>
                                                @else
                                                    <span class="branch-name">Main Location</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="status-badge success">
                                                    <i class="fa fa-check me-1"></i>
                                                    Successful
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($history['checkins']->hasPages())
                            <div class="pagination-wrapper">
                                {{ $history['checkins']->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fa fa-history"></i>
                        </div>
                        <h5>No Check-in History</h5>
                        <p>You haven't checked in yet. Start your fitness journey today!</p>
                        <a href="{{ route('user.checkin.self', $siteSetting->slug) }}" class="primary-btn">
                            <i class="fa fa-sign-in-alt me-2"></i>
                            Check In Now
                        </a>
                    </div>
                @endif

                <div class="action-buttons mt-5">
                    <a href="{{ route('user.checkin.self', $siteSetting->slug) }}" class="secondary-btn">
                        <i class="fa fa-arrow-left me-2"></i>
                        Back to Check-in
                    </a>
                    <a href="{{ route('user.checkin.personal-qr', $siteSetting->slug) }}" class="primary-btn">
                        <i class="fa fa-qrcode me-2"></i>
                        My QR Code
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- History Section End -->

@endsection
