@extends('layout.admin.master')

@section('title' , 'Subscription Details')

@section('main-breadcrumb', 'Subscription')
@section('main-breadcrumb-link', route('subscriptions.index'))

@section('sub-breadcrumb','Show Subscription')

@section('content')

<div class="row">
    <!-- Subscription Information -->
    <div class="col-md-6 mb-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Subscription Information</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Subscription ID:</div>
                    <div class="col-md-8">{{ $subscription->id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Status:</div>
                    <div class="col-md-8">
                        <span class="badge badge-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'pending' ? 'warning' : ($subscription->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Start Date:</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">End Date:</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Duration:</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($subscription->start_date)->diffInDays($subscription->end_date) }} days</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Created At:</div>
                    <div class="col-md-8">{{ $subscription->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Updated At:</div>
                    <div class="col-md-8">{{ $subscription->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="col-md-6 mb-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Information</h3>
            </div>
            <div class="card-body">
                @if($subscription->user)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">User ID:</div>
                    <div class="col-md-8">{{ $subscription->user->id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Name:</div>
                    <div class="col-md-8">{{ $subscription->user->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Email:</div>
                    <div class="col-md-8">{{ $subscription->user->email }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Phone:</div>
                    <div class="col-md-8">{{ $subscription->user->phone ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Role:</div>
                    <div class="col-md-8">
                        @foreach($subscription->user->roles as $role)
                            <span class="badge bg-primary text-white">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="alert alert-warning">User information not available</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Membership Information -->
    <div class="col-md-6 mb-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Membership Information</h3>
            </div>
            <div class="card-body">
                @if($subscription->membership)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Membership ID:</div>
                    <div class="col-md-8">{{ $subscription->membership->id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Name:</div>
                    <div class="col-md-8">{{ $subscription->membership->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Description:</div>
                    <div class="col-md-8">{{ $subscription->membership->general_description ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Original Price:</div>
                    <div class="col-md-8">{{ $subscription->membership->price }} {{ config('app.currency', 'USD') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Duration:</div>
                    <div class="col-md-8">{{ $subscription->membership->period }}</div>
                </div>
                @else
                <div class="alert alert-warning">Membership information not available</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Branch Information -->
    <div class="col-md-6 mb-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Branch Information</h3>
            </div>
            <div class="card-body">
                @if($subscription->branch)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Branch ID:</div>
                        <div class="col-md-8">{{ $subscription->branch->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Name:</div>
                        <div class="col-md-8">{{ $subscription->branch->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Type:</div>
                        <div class="col-md-8">{{ $subscription->branch->type ?? 'Not Specified' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Address:</div>
                        <div class="col-md-8">{{ $subscription->branch->location ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Phone:</div>
                        <div class="col-md-8">{{ $subscription->branch->phone ?? 'N/A' }}</div>
                    </div>
                @else
                    <div class="alert alert-warning">Branch information not available</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="col-md-12 mb-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment Information</h3>
            </div>
            <div class="card-body">
                @if($subscription->membership->payment)
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Payment ID:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->id }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Amount:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->amount }} {{ config('app.currency', 'USD') }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Payment Method:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->payment_method ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Transaction ID:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->transaction_id ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Status:</div>
                            <div class="col-md-8">
                                <span class="badge badge-{{ $subscription->membership->payment->status === 'completed' ? 'success' : ($subscription->membership->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($subscription->membership->payment->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Completed At:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->completed_at ? $subscription->membership->payment->completed_at->format('M d, Y H:i') : 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Failed At:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->failed_at ? $subscription->membership->payment->failed_at->format('M d, Y H:i') : 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Created At:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                @if($subscription->membership->payment->offer)
                <hr>
                <h5 class="mb-3">Applied Offer</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Offer Name:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->offer->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Discount Type:</div>
                            <div class="col-md-8">{{ ucfirst($subscription->membership->payment->offer->discount_type) }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Discount Value:</div>
                            <div class="col-md-8">
                                {{ $subscription->membership->payment->offer->discount_value }}
                                {{ $subscription->membership->payment->offer->discount_type === 'percentage' ? '%' : config('app.currency', 'EGP') }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Valid Until:</div>
                            <div class="col-md-8">{{ $subscription->membership->payment->offer->valid_until ? \Carbon\Carbon::parse($subscription->membership->payment->offer->valid_until)->format('M d, Y') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                @endif

                @else
                <div class="alert alert-warning">No payment information available for this subscription</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        @can('edit_subscriptions')
                        <a href="{{ route('subscriptions.edit', $subscription->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Subscription
                        </a>
                        @endcan
                        @can('view_subscriptions')
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        @endcan
                    </div>
                    @can('delete_subscriptions')
                    <div>
                        <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Subscription
                            </button>
                        </form>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
