@extends('layout.user.master')

@section('title', 'My Profile')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>My Profile</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home', ['siteSetting' => $siteSetting]) }}">Home</a>
                            <span>Profile</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Profile Section Begin -->
    <section class="profile-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="profile-header-section">
                        <div class="profile-avatar">
                            <img src="{{ $user->user_image }}" alt="{{ $user->name }}" class="rounded-circle">
                        </div>
                        <div class="profile-info">
                            <h3>{{ $user->name }}</h3>
                            <p class="text-white">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-white"><i class="fa fa-phone"></i> {{ $user->phone }}</p>
                            @endif
                            @if($user->address)
                                <p class="text-white"><i class="fa fa-map-marker"></i> {{ $user->address }}</p>
                            @endif
                        </div>
                        <div class="profile-actions">
                            <a href="{{ route('profile.edit', ['siteSetting' => $siteSetting]) }}" class="btn btn-primary bg-primary-gym">
                                <i class="fa fa-edit"></i> Edit Profile
                            </a>
                        </div>
                    </div>

                    <div class="profile-details-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Full Name:</label>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Email:</label>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Phone:</label>
                                    <span>{{ $user->phone ?? 'Not provided' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Gender:</label>
                                    <span>{{ $user->gender ?? 'Not specified' }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="detail-item">
                                    <label>Address:</label>
                                    <span>{{ $user->address ?? 'Not provided' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Account Status:</label>
                                    <span class="badge {{ $user->status ? 'badge-success' : 'badge-danger' }}">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Member Since:</label>
                                    <span>{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($user->subscriptions->count() > 0)
                    <div class="profile-memberships-section">
                        <h4>My Memberships</h4>
                        <div class="row">
                            @foreach($user->subscriptions as $subscription)
                            <div class="col-md-6 mb-3">
                                <div class="membership-item">
                                    <h5>{{ $subscription->membership->name }}</h5>
                                    <p class="text-muted">{{ $subscription->membership->subtitle }}</p>
                                    <div class="membership-status">
                                        <span class="badge {{ $subscription->status ? 'badge-success' : 'badge-warning' }}">
                                            {{ $subscription->status ? 'Active' : 'Expired' }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        Expires: {{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y') : 'No expiration' }}
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="profile-actions-section">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('profile.edit', ['siteSetting' => $siteSetting]) }}" class="btn btn-primary btn-block">
                                    <i class="fa fa-edit"></i> Edit Profile
                                </a>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('profile.delete', ['siteSetting' => $siteSetting]) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fa fa-trash"></i> Delete Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile Section End -->
@endsection

@section('css')
<style>
.profile-section {
    background-color: #151515;
    min-height: 100vh;
    padding: 60px 0;
}

.profile-header-section {
    background: linear-gradient(135deg, #0c0806 0%, #f36001 86%);
    color: white;
    padding: 40px;
    text-align: center;
    position: relative;
    border-radius: 15px;
    margin-bottom: 40px;
}

.profile-avatar {
    margin-bottom: 20px;
}

.profile-avatar img {
    width: 120px;
    height: 120px;
    border: 5px solid rgba(255,255,255,0.3);
    object-fit: cover;
}

.profile-info h3 {
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: 600;
    color: white;
}

.profile-info p {
    margin: 5px 0;
    opacity: 0.9;
    color: white;
}

.profile-actions {
    position: absolute;
    top: 20px;
    right: 20px;
}

.profile-details-section {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 40px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.detail-item {
    margin-bottom: 25px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.detail-item label {
    font-weight: 600;
    color: white;
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item span {
    color: #ffffff;
    font-size: 16px;
}

.profile-memberships-section {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 40px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.profile-memberships-section h4 {
    margin-bottom: 25px;
    color: #f36001;
    border-bottom: 2px solid #f36001;
    padding-bottom: 15px;
    font-size: 24px;
    font-weight: 600;
}

.membership-item {
    background: rgba(255, 255, 255, 0.1);
    padding: 25px;
    border-radius: 10px;
    border-left: 4px solid #f36001;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.membership-item h5 {
    margin: 0 0 10px 0;
    color: #ffffff;
    font-size: 18px;
    font-weight: 600;
}

.membership-item p {
    color: rgba(255, 255, 255, 0.7);
}

.membership-status {
    margin: 15px 0;
}

.membership-item small {
    color: rgba(255, 255, 255, 0.6);
}

.profile-actions-section {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn {
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #0c0806 0%, #f36001 86%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(243, 96, 1, 0.4);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    color: white;
}

.btn-block {
    width: 100%;
}

.badge {
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #28a745;
    color: white;
}

.badge-danger {
    background: #dc3545;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #212529;
}

@media (max-width: 768px) {
    .profile-actions {
        position: static;
        margin-top: 20px;
    }
    
    .profile-header-section {
        text-align: center;
        padding: 30px 20px;
    }
    
    .profile-details-section,
    .profile-memberships-section,
    .profile-actions-section {
        padding: 25px 20px;
    }
    
    .detail-item {
        padding: 15px;
    }
}
</style>
@endsection
