@extends('layout.admin.master')

@section('title' , 'Add Subscription')

@section('main-breadcrumb', 'Subscription')
@section('main-breadcrumb-link', route('subscriptions.index'))

@section('sub-breadcrumb','Create Subscription')

@section('content')

    <div class="col-md-12 mb-md-5 mb-xl-10">
        <form action="{{ route('subscriptions.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body row">
                    <div class="mb-10 col-md-6">
                        <label for="user_id" class="required form-label">User</label>
                        @php
                            $options = [];
                            foreach($users as $user){
                                $options[] = [
                                    'value' => $user->id,
                                    'label' => $user->name
                                ];
                            }
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'user_id',
                            'id' => 'user_id',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="membership_id" class="required form-label">Membership</label>
                        @php
                            $options = [];
                            foreach($memberships as $membership){
                                $options[] = [
                                    'value' => $membership->id,
                                    'label' => $membership->name
                                ];
                            }
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'membership_id',
                            'id' => 'membership_id',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="branch_id" class="required form-label">Branch</label>
                        @php
                            $options = [];
                            foreach($branches as $branch){
                                $options[] = [
                                    'value' => $branch->id,
                                    'label' => $branch->name
                                ];
                            }
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'branch_id',
                            'id' => 'branch_id',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="status" class="required form-label">Status</label>
                        @php
                            $options = [
                                ['value' => 'active', 'label' => 'Active'],
                                ['value' => 'pending', 'label' => 'Pending'],
                                ['value' => 'cancelled', 'label' => 'Cancelled'],
                                ['value' => 'expired', 'label' => 'Expired'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'status',
                            'id' => 'status',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="apply_offer" class="required form-label">Want to apply offer ?</label>
                        @php
                            $options = [
                                ['value' => 'yes', 'label' => 'yes'],
                                ['value' => 'no', 'label' => 'No'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'apply_offer',
                            'id' => 'apply_offer',
                        ])
                    </div>
                    <div class="mb-10 col-md-6 d-none" id="offersListContainer">
                        <label for="offer_id" class="required form-label">Select Offer</label>
                        @include('_partials.select',[
                            'options' => [],
                            'name' => 'offer_id',
                            'id' => 'offer_id',
                        ])
                    </div>        
                    <div class="mb-10 col-md-6 d-none" id="amountPaidContainer">
                        <label for="amount" class="required form-label">Amount Paid</label>
                        <input type="text" id="amount" value="{{ old('amount') }}" name="amount" class="form-control form-control-solid" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="start_date" class="required form-label">Start date</label>
                        <input type="date" id="start_date" value="{{ old('start_date') }}" name="start_date" class="form-control form-control-solid" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="end_date" class="required form-label">End date</label>
                        <input type="date" id="end_date" value="{{ old('end_date') }}" name="end_date" class="form-control form-control-solid" required/>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-dark">Cancel</a>
                    </div>
    
                </div>
            </div>
        </form>
    </div>

@endsection

@section('js')
    @include('admin.subscriptions._partials.subscription-script', ['selectedOfferId' => null])
@endsection