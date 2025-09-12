@extends('layout.admin.master')

@section('title', 'Cash Payments Management')

@section('main-breadcrumb', 'Cash Payments')
@section('main-breadcrumb-link', route('admin.cash-payments.index'))

@section('sub-breadcrumb', 'Manage Cash Payments')

@section('content')

<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white">Total Cash Pending</h6>
                        <h3 class="mb-0 text-white">{{ number_format($statistics['total_cash_pending'], 2) }} EGP</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white">Total Cash Collected</h6>
                        <h3 class="mb-0 text-white">{{ number_format($statistics['total_cash_collected'], 2) }} EGP</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white">Pending Items</h6>
                        <h3 class="mb-0 text-white">{{ $statistics['pending_count'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white">Collected Items</h6>
                        <h3 class="mb-0 text-white">{{ $statistics['collected_count'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list-check fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-5">
    <div class="card-header">
        <h5 class="card-title">Filters</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.cash-payments.index') }}" id="filters-form">
            <div class="row">
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="cash_pending" {{ $filters['status'] === 'cash_pending' ? 'selected' : '' }}>Cash Pending</option>
                        <option value="cash_collected" {{ $filters['status'] === 'cash_collected' ? 'selected' : '' }}>Cash Collected</option>
                        <option value="pending" {{ $filters['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $filters['status'] === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="payment_type" class="form-label">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="bookings" {{ $filters['payment_type'] === 'bookings' ? 'selected' : '' }}>Bookings</option>
                        <option value="payments" {{ $filters['payment_type'] === 'payments' ? 'selected' : '' }}>Payments</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $filters['branch_id'] == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Name, Email, Phone" value="{{ $filters['search'] ?? '' }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.cash-payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                    <a href="{{ route('admin.cash-payments.export') }}?{{ http_build_query($filters) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Cash Payments Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Cash Payments</h5>
    </div>
    <div class="card-body">
        @if($cashPayments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>User</th>
                            <th>Branch</th>
                            <th>Item</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cashPayments as $payment)
                            <tr>
                                <td>{{ $payment['id'] }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment['type'] === 'booking' ? 'info' : 'primary' }} text-white">
                                        {{ ucfirst($payment['type']) }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $payment['user_name'] }}</strong><br>
                                        <small class="text-muted">{{ $payment['user_email'] }}</small><br>
                                        <small class="text-muted">{{ $payment['user_phone'] }}</small>
                                    </div>
                                </td>
                                <td>{{ $payment['branch_name'] }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $payment['paymentable_type'] ?? $payment['bookable_type'] }}</strong><br>
                                        <small class="text-muted">{{ $payment['paymentable_name'] ?? $payment['bookable_name'] }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ number_format($payment['amount'], 2) }} EGP</strong>
                                </td>
                                <td>
                                    @if($payment['status'] === 'cash_pending' || $payment['status'] === 'pending')
                                        <span class="badge bg-warning text-white">Pending</span>
                                    @elseif($payment['status'] === 'cash_collected' || $payment['status'] === 'completed')
                                        <span class="badge bg-success text-white">Collected</span>
                                    @else
                                        <span class="badge bg-secondary text-white">{{ ucfirst($payment['status']) }}</span>
                                    @endif
                                </td>
                                <td>{{ $payment['created_at']->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($payment['status'] === 'cash_pending' || $payment['status'] === 'pending')
                                        <form method="POST" action="{{ route('admin.cash-payments.mark-collected') }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $payment['id'] }}">
                                            <input type="hidden" name="type" value="{{ $payment['type'] }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Mark Collected
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.cash-payments.mark-pending') }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $payment['id'] }}">
                                            <input type="hidden" name="type" value="{{ $payment['type'] }}">
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-clock"></i> Mark Pending
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No cash payments found</h5>
                <p class="text-muted">Try adjusting your filters or search criteria.</p>
            </div>
        @endif
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Auto-submit form when filters change
        $('#status, #payment_type, #branch_id').change(function() {
            $('#filters-form').submit();
        });

        // Search with delay
        let searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                $('#filters-form').submit();
            }, 500);
        });

        // Date range auto-submit
        $('#date_from, #date_to').change(function() {
            $('#filters-form').submit();
        });
    });
</script>
@stop
