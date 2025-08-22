@extends('layout.user.master')

@section('title', 'Verify Invitation - ' . $siteSetting->gym_name)

@section('content')
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Verify Invitation</h2>
                    <div class="bt-option">
                        <a href="{{ route('user.home', $siteSetting) }}">Home</a>
                        <span>Verify Invitation</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Verification Section Begin -->
<section class="pricing-section spad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary-gym text-white">
                        <h3 class="mb-0">Verify Gym Invitation</h3>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show " role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(isset($invitation) && $invitation)
                            <!-- Invitation Details -->
                            <div class="alert alert-success">
                                <h5 class="alert-heading">Invitation Verified Successfully! And has been marked as used.</h5>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-secondary-color">Invitee Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $invitation->invitee_name ?: 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $invitation->invitee_email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $invitation->invitee_phone }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary-color">Invitation Details</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Membership:</strong></td>
                                            <td>{{ $invitation->membership->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sent By:</strong></td>
                                            <td>{{ $invitation->inviter->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sent Date:</strong></td>
                                            <td>{{ $invitation->created_at->format('F j, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Verified By:</strong></td>
                                            <td>{{ Auth::user()->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Verified Date:</strong></td>
                                            <td>{{ now()->format('F j, Y \a\t g:i A') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('user.home',  $siteSetting) }}" class="btn btn-primary-gym">
                                    <i class="fa-solid fa-house"></i> User are welcome
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Verification Section End -->
@endsection
