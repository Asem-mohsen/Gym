@extends('layout.admin.master')

@section('title', 'Invitations Management')

@section('main-breadcrumb', 'Invitations')
@section('main-breadcrumb-link', route('invitations.index'))

@section('sub-breadcrumb', 'All Invitations')

@section('content')

<div class="card shadow mb-4">
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('invitations.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by email, phone, name...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="branch_id">Branch</label>
                        <select class="form-control" id="branch_id" name="branch_id">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->getTranslation('name', 'en') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="membership_id">Membership</label>
                        <select class="form-control" id="membership_id" name="membership_id">
                            <option value="">All Memberships</option>
                            @foreach($memberships as $membership)
                                <option value="{{ $membership->id }}" {{ request('membership_id') == $membership->id ? 'selected' : '' }}>
                                    {{ $membership->getTranslation('name', 'en') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('invitations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Invitations Table -->
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Inviter</th>
                        <th>Membership</th>
                        <th>Invitee Name</th>
                        <th>Invitee Email</th>
                        <th>Invitee Phone</th>
                        <th>Status</th>
                        <th>Expires At</th>
                        <th>Sent At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invitations as $invitation)
                        <tr>
                            <td>{{ $invitation->id }}</td>
                            <td>{{ $invitation->inviter->name }}</td>
                            <td>{{ $invitation->membership->getTranslation('name', 'en') }}</td>
                            <td>{{ $invitation->invitee_name ?? 'N/A' }}</td>
                            <td>{{ $invitation->invitee_email }}</td>
                            <td>{{ $invitation->invitee_phone }}</td>
                            <td>
                                @if($invitation->is_used)
                                    <span class="badge badge-success">Used</span>
                                @elseif($invitation->isExpired())
                                    <span class="badge badge-warning">Expired</span>
                                @else
                                    <span class="badge badge-primary">Active</span>
                                @endif
                            </td>
                            <td>{{ $invitation->expires_at ? $invitation->expires_at->format('M j, Y H:i') : 'N/A' }}</td>
                            <td>{{ $invitation->created_at->format('M j, Y H:i') }}</td>
                            <td>
                                @can('view_invitations')
                                    <a href="{{ route('invitations.show', $invitation) }}" 
                                    class="btn  btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No invitations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
    @include('_partials.dataTable-script')
@endsection