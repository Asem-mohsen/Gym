@extends('layout.user.master')

@section('title', 'My Invitations - ' . $siteSetting->gym_name)

@section('css')
    @include('user.invitations.assets.style')
@endsection

@section('content')
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>My Invitations</h2>
                    <div class="bt-option">
                        <a href="{{ route('user.home', $siteSetting) }}">Home</a>
                        <span>My Invitations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Invitations Section Begin -->
<section class="pricing-section spad">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="text-white">My Invitations</h3>
                    @if($remaining_invitations > 0)
                        <button type="button" class="btn btn-primary" onclick="openInvitationModal()">
                            <i class="fa fa-plus"></i> Send New Invitation
                        </button>
                    @else
                        <button class="btn btn-secondary" disabled>
                            <i class="fa fa-plus"></i> No Invitations Left
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-color-primary-gym">
                    <div class="card-body bg-primary-gym">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h5 class="text-white">{{ $remaining_invitations }}</h5>
                                <p class="text-white">Remaining Invitations</p>
                            </div>
                            <div class="col-md-3">
                                <h5 class="text-white">{{ $active_invitations->count() }}</h5>
                                <p class="text-white">Active Invitations</p>
                            </div>
                            <div class="col-md-3">
                                <h5 class="text-white">{{ $used_invitations->count() }}</h5>
                                <p class="text-white">Used Invitations</p>
                            </div>
                            <div class="col-md-3">
                                <h5 class="text-white">{{ $expired_invitations->count() }}</h5>
                                <p class="text-white">Expired Invitations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invitations Tabs -->
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="invitationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                            Active ({{ $active_invitations->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="used-tab" data-bs-toggle="tab" data-bs-target="#used" type="button" role="tab">
                            Used ({{ $used_invitations->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="expired-tab" data-bs-toggle="tab" data-bs-target="#expired" type="button" role="tab">
                            Expired ({{ $expired_invitations->count() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="invitationTabsContent">
                    <!-- Active Invitations -->
                    <div class="tab-pane fade show active" id="active" role="tabpanel">
                        @if($active_invitations->count() > 0)
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Invitee</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Sent Date</th>
                                            <th>Expires</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($active_invitations as $invitation)
                                            <tr>
                                                <td>{{ $invitation->invitee_name ?: 'N/A' }}</td>
                                                <td>{{ $invitation->invitee_email }}</td>
                                                <td>{{ $invitation->invitee_phone }}</td>
                                                <td>{{ $invitation->created_at->format('M j, Y') }}</td>
                                                <td>{{ $invitation->expires_at->format('M j, Y') }}</td>
                                                <td>
                                                    <span class="text-secondary-color">Invitation sent successfully</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Active Invitations</h5>
                                <p class="text-muted">You don't have any active invitations at the moment.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Used Invitations -->
                    <div class="tab-pane fade" id="used" role="tabpanel">
                        @if($used_invitations->count() > 0)
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Invitee</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Used By</th>
                                            <th>Used Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($used_invitations as $invitation)
                                            <tr>
                                                <td>{{ $invitation->invitee_name ?: 'N/A' }}</td>
                                                <td>{{ $invitation->invitee_email }}</td>
                                                <td>{{ $invitation->invitee_phone }}</td>
                                                <td>{{ $invitation->usedBy->name ?? 'N/A' }}</td>
                                                <td>{{ $invitation->used_at->format('M j, Y') }}</td>
                                                <td>
                                                    <span class="text-secondary-color">Invitation used successfully</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa fa-check-circle fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Used Invitations</h5>
                                <p class="text-muted">None of your invitations have been used yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Expired Invitations -->
                    <div class="tab-pane fade" id="expired" role="tabpanel">
                        @if($expired_invitations->count() > 0)
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Invitee</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Sent Date</th>
                                            <th>Expired Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expired_invitations as $invitation)
                                            <tr>
                                                <td>{{ $invitation->invitee_name ?: 'N/A' }}</td>
                                                <td>{{ $invitation->invitee_email }}</td>
                                                <td>{{ $invitation->invitee_phone }}</td>
                                                <td>{{ $invitation->created_at->format('M j, Y') }}</td>
                                                <td>{{ $invitation->expires_at->format('M j, Y') }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="resendInvitation({{ $invitation->id }})">
                                                        <i class="fa fa-refresh"></i> Resend
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa fa-clock fa-3x text-muted mb-3"></i>
                                <h5 class="text-white">No Expired Invitations</h5>
                                <p class="text-muted">All your invitations are still valid.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Invitations Section End -->

<!-- Invitation Modal -->
<div class="modal fade" id="invitationModal" tabindex="-1" aria-labelledby="invitationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invitationModalLabel">Send Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.invitations.store', $siteSetting) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <p class="text-muted">
                            Share the fitness journey with your friends and family! Invite them to join {{ $siteSetting->gym_name }} and experience the benefits of our premium facilities and expert trainers.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="invitee_name" class="form-label">Friend's Name (Optional)</label>
                        <input type="text" 
                               class="form-control @error('invitee_name') is-invalid @enderror" 
                               id="invitee_name" 
                               name="invitee_name" 
                               value="{{ old('invitee_name') }}"
                               placeholder="Enter your friend's name">
                        @error('invitee_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="invitee_email" class="form-label">Email Address *</label>
                        <input type="email" 
                               class="form-control @error('invitee_email') is-invalid @enderror" 
                               id="invitee_email" 
                               name="invitee_email" 
                               value="{{ old('invitee_email') }}"
                               placeholder="Enter email address"
                               required>
                        @error('invitee_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="invitee_phone" class="form-label">Phone Number *</label>
                        <input type="text" 
                               class="form-control @error('invitee_phone') is-invalid @enderror" 
                               id="invitee_phone" 
                               name="invitee_phone" 
                               value="{{ old('invitee_phone') }}"
                               placeholder="Enter phone number"
                               required>
                        @error('invitee_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fa fa-info-circle"></i>
                            Your friend will receive an email with a QR code that they can present at the gym to redeem their invitation.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('Js')
    @include('user.invitations.assets.scripts')
@endsection
