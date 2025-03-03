@extends('layout.master')

@section('title' , 'Edit Subscription')

@section('content')

    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>

                <x-authenticated-user-info />

            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <form action="{{ route('subscriptions.update' , $subscription->id) }}" method="post">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <span class="btn btn-dark btn-sm ms-auto m-2">Edit</span>
                                <p class="mb-0">Subscription</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Subscription Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="user_id" class="form-control-label">User</label>
                                    <select class="form-control" name="user_id" id="user_id">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == $subscription->user_id)>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="membership_id" class="form-control-label">Membership</label>
                                    <select class="form-control" name="membership_id" id="membership_id">
                                        @foreach ($memberships as $membership)
                                            <option value="{{ $membership->id }}" @selected($membership->id == $subscription->membership_id)>{{ $membership->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('membership_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="branch_id" class="form-control-label">Branch</label>
                                    <select class="form-control" name="branch_id" id="branch_id">
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" @selected($branch->id == $subscription->branch_id) >{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-control-label">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="active" @selected( $subscription->status == 'active')>Active</option>
                                        <option value="pending" @selected( $subscription->status == 'pending')>Pending</option>
                                        <option value="cancelled" @selected( $subscription->status == 'cancelled')>Cancelled</option>
                                        <option value="expired" @selected( $subscription->status == 'expired')>Expired</option>
                                    </select>
                                    @error('status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                @if($subscription->status == 'active')
                                    <div class="col-md-6" id="offerContainer">
                                        <div class="form-group">
                                            <label class="form-control-label">Want to apply offer ?</label>
                                            <select id="apply_offer" class="form-control">
                                                <option value="no" @selected(!$subscription->payment->offer_id)>No</option>
                                                <option value="yes" @selected($subscription->payment->offer_id)>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($subscription->payment->offer_id)
                                    <div class="col-md-6" id="offersListContainer">
                                        <div class="form-group">
                                            <label for="offer_id" class="form-control-label">Select Offer</label>
                                            <select class="form-control" name="offer_id" id="offer_id">
                                                <option value="">Loading offers...</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($subscription->payment)
                                    <div class="col-md-6" id="amountPaidContainer">
                                        <div class="form-group">
                                            <label for="amount" class="form-control-label">Amount Paid</label>
                                            <input class="form-control" id="amount" type="number" name="amount" value="{{ $subscription->payment->amount ?? 0 }}">
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date" class="form-control-label w-100">Start date</label>
                                        <input class="form-control" id="start_date" type="date" name="start_date" value="{{ $subscription->start_date }}" required>
                                    </div>
                                    @error('start_date')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date" class="form-control-label w-100">End date</label>
                                        <input class="form-control" id="end_date" type="date" name="end_date" value="{{ $subscription->end_date }}" required>
                                    </div>
                                    @error('end_date')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <hr class="horizontal dark">

                            <p class="text-uppercase text-sm">Control</p>
                            <div class="justify-content-center row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-md btn-success w-100 mt-4 mb-0">Update</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a href="{{ route('subscriptions.index')}}" class="btn btn-md btn-danger w-100 mt-4 mb-0">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection


@section('Js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @include('admin.subscriptions._partials.subscription-script')
@endsection