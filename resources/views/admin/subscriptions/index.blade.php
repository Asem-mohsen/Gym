@extends('layout.master')

@section('title' , 'Subscriptions')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Subscriptions</span>
                        <span class="info-box-number">{{ $counts['total'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Active Subscriptions</span>
                        <span class="info-box-number">{{ $counts['active'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Expired Subscriptions</span>
                        <span class="info-box-number">{{ $counts['expired'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Pending Subscriptions</span>
                        <span class="info-box-number">{{ $counts['pending'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Buttons --}}
                <div class="btn-group w-fit pb-2">
                    <a href="{{ route('subscriptions.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add Manual Subscripton</a>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Membership</th>
                            <th>From - To</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($subscriptions as $subscription)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="{{ asset('assets/dist/img/avatar.png') }}" class="avatar avatar-sm me-3" alt="user1">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <span class="text-sm">User Id: {{$subscription->user->id}}</span>
                                            <h6 class="mb-0 text-sm">{{$subscription->user->name}}</h6>
                                            <span>{{$subscription->user->email}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$subscription->membership->name}}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <p class="text-xs font-weight-bold mb-0">From - {{ date('d-M-Y' , strtotime($subscription->start_date)) }}</p>
                                        <p class="text-xs font-weight-bold mb-0">To - {{ date('d-M-Y' , strtotime($subscription->end_date)) }}</p>
                                    </div>
                                </td>
                                <td>
                                    @if ($order->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @elseif ($order->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($order->status == 'cancelled')
                                        <span class="badge badge-danger">Cancelled</span>
                                    @elseif ($order->status == 'expired')
                                        <span class="badge badge-info">Expired</span>
                                    @endif
                                </td>
                                <td class="d-flex justify-content-around gap-1 align-items-baseline">
                                    <a href="{{route('subscriptions.show' , $subscription->id )}}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View">
                                        View
                                    </a>
                                    <a href="{{route('subscriptions.edit' , $subscription->id )}}" class="text-success font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('subscriptions.destroy' ,$subscription->id )}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button  class="border-0 bg-transparent p-0 text-danger font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Remove">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('Js')
     <!-- Page specific script -->
     @include('_partials.scripts.datatables-init-script')
@stop
