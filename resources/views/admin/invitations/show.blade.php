@extends('layout.admin.master')

@section('title', 'Invitation Details')

@section('main-breadcrumb', 'Invitations')
@section('main-breadcrumb-link', route('invitations.index'))

@section('sub-breadcrumb', 'Invitation Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Invitation Information</h6>
                <div>
                    <a href="{{ route('invitations.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary">Basic Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Invitation ID:</strong></td>
                                <td>#{{ $invitation->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($invitation->is_used)
                                        <span class="badge badge-success">Used</span>
                                    @elseif($invitation->isExpired())
                                        <span class="badge badge-warning">Expired</span>
                                    @else
                                        <span class="badge badge-primary">Active</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>QR Code:</strong></td>
                                <td><code>{{ $invitation->qr_code }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Created At:</strong></td>
                                <td>{{ $invitation->created_at->format('F j, Y \a\t g:i A') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Expires At:</strong></td>
                                <td>{{ $invitation->expires_at ? $invitation->expires_at->format('F j, Y \a\t g:i A') : 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary">Invitee Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $invitation->invitee_email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>{{ $invitation->invitee_phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $invitation->invitee_name ?? 'N/A' }}</td>
                            </tr>
                            @if($invitation->is_used)
                                <tr>
                                    <td><strong>Used At:</strong></td>
                                    <td>{{ $invitation->used_at->format('F j, Y \a\t g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Used By:</strong></td>
                                    <td>{{ $invitation->usedBy->name ?? 'N/A' }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Inviter Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary align-self-center">Inviter Information</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($invitation->inviter->user_image)
                        <img src="{{ $invitation->inviter->user_image }}" 
                             alt="Inviter" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                    @endif
                </div>
                <h6 class="text-center">{{ $invitation->inviter->name }}</h6>
                <p class="text-center text-muted">{{ $invitation->inviter->email }}</p>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-primary">{{ $invitation->inviter->sentInvitations()->count() }}</h6>
                        <small class="text-muted">Total Invitations</small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-success">{{ $invitation->inviter->sentInvitations()->used()->count() }}</h6>
                        <small class="text-muted">Used Invitations</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gym & Membership Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary align-self-center">Gym & Membership</h6>
            </div>
            <div class="card-body">
                <h6 class="text-primary">{{ $invitation->gym->getTranslation('gym_name', 'en') }}</h6>
                <p class="text-muted mb-3">{{ $invitation->gym->getTranslation('address', 'en') }}</p>
                
                <hr>
                
                <h6 class="text-success">{{ $invitation->membership->getTranslation('name', 'en') }}</h6>
                <p class="text-muted mb-2">{{ $invitation->membership->getTranslation('subtitle', 'en') }}</p>
                <p class="mb-0"><strong>Price:</strong> ${{ number_format($invitation->membership->price, 2) }}</p>
            </div>
        </div>

        <!-- QR Code and Copy Link Section -->
        @if(!$invitation->is_used && !$invitation->isExpired())
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary align-self-center">QR Code</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        {!! QrCode::size(200)->generate($invitation->qr_code_url) !!}
                    </div>
                    <small class="text-muted">Scan this QR code to use the invitation</small>

                    <div class="input-group mt-10">
                        <input type="text" class="form-control" value="{{ $invitation->qr_code_url }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('{{ $invitation->qr_code_url }}')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">Share this URL with the invitee</small>
                </div>
            </div>

        @endif
    </div>
</div>
@endsection
