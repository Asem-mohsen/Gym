@extends('layout.admin.master')

@section('title' , 'Payments')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <!--Income-->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header mx-4 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                    <i class="fas fa-landmark opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0">Income</h6>
                                    <span class="text-xs">Total</span>
                                    <hr class="horizontal dark my-3">
                                    <h5 class="mb-0">{{$payments['total_paid'] . ' EGP'}}</h5>
                                </div>
                            </div>
                        </div>
                        <!--Outcome-->
                        <div class="col-md-4 mt-md-0 mt-4">
                            <div class="card">
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                <i class="fa-solid fa-circle-minus opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h6 class="text-center mb-0">Outcome</h6>
                                <span class="text-xs">Total</span>
                                <hr class="horizontal dark my-3">
                                <h5 class="mb-0">0 EGP</h5>
                            </div>
                            </div>
                        </div>
                        <!--Pending-->
                        <div class="col-md-4 mt-md-0 mt-4">
                            <div class="card">
                                <div class="card-header mx-4 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                    <i class="fa-solid fa-circle-minus opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0">Pending</h6>
                                    <span class="text-xs">Total</span>
                                    <hr class="horizontal dark my-3">
                                    <h5 class="mb-0">{{$payments['total_pending']. ' EGP'}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Most Payment Method Used-->
                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card mt-4">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0">Most Payment Method Used</h6>
                        </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                        <div class="col-md-6 mb-md-0 mb-4">
                            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row" style="min-height: 103px;">
                            <img class="w-10 me-3 mb-0" src="{{asset('assets/dist/img/credit/mastercard.png')}}" alt="logo">
                            <h6 class="mb-0"> User Paid with Cards</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row" style="min-height: 103px;">
                            <i class="fa-solid fa-hand-holding-dollar w-10 me-3 mb-0"></i>
                            <h6 class="mb-0">User Paid in Cash</h6>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 mt-4">
            <div class="card h-100 mb-4">
                <div class="card-header pb-0 px-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-0">Payments</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 p-3">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Old Transaction</h6>
                    <!--oldest_transactions-->
                    <ul class="list-group">
                        @forelse ($payments['oldest_transactions'] as $oldPayments)
                            @if($oldPayments->status == 'completed')

                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-arrow-up"></i></button>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">
                                                {{$oldPayments->user->name}}
                                            </h6>
                                            <span class="text-xs">
                                                {{ date('d M Y' , strtotime($oldPayments->created_at)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold">
                                        {{"+ " . $oldPayments->amount . "EGP"}}
                                    </div>
                                </li>
                            
                            @elseif($oldPayments->status  == 'pending')

                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-exclamation"></i></button>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">
                                                {{$oldPayments->user->name ?? '-'}}
                                            </h6>
                                            <span class="text-xs">
                                                {{ date('d M Y' , strtotime($oldPayments->created_at)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center text-danger text-sm font-weight-bold">
                                        {{" - " . $oldPayments->amount . "EGP"}}
                                    </div>
                                </li>

                            @endif

                        @empty

                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-icon-only btn-rounded btn-outline-dark mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-exclamation"></i></button>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">-</h6>
                                        <span class="text-xs">{{ date('d M Y') }} </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-dark text-sm font-weight-bold">
                                    No Old Transaction
                                </div>
                            </li>
                        @endforelse
                    </ul>

                    {{-- <h6 class="text-uppercase text-body text-xs font-weight-bolder my-3">Past</h6>
                    <ul class="list-group">
                        @if($past)
                            @foreach($past AS $pastTransaction)
                                @php $transactionDate = date('d M Y' , strtotime($pastTransaction->created_at)); @endphp
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"><i class="fas fa-arrow-up"></i></button>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">
                                                    {{$newestTransaction->user->name ?? '-'}}
                                                </h6>
                                                <span class="text-xs">
                                                    {{$transactionDate}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold">
                                                {{"+" . $pastTransaction->total_amount . "EGP"}}
                                        </div>
                                    </li>
                            @endforeach
                        @endif
                    </ul> --}}
                </div>
            </div>
        </div>
        
        <div class="col-md-7 mt-4">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Newest Transaction Information</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <ul class="list-group">
                        @forelse ($payments['newest_transactions'] as $payment)
                            <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-3 text-sm">
                                        {{$payment->user->name }}
                                    </h6>
                                    <span class="mb-2 text-xs">
                                        Product: 
                                        <span class="text-dark font-weight-bold ms-sm-2">
                                            {{$payment->paymentable->name}}
                                        </span>
                                    </span>

                                    <span class="mb-2 text-xs">
                                        Email Address: 
                                        <span class="text-dark ms-sm-2 font-weight-bold">
                                            {{$payment->user->email}}                                                
                                        </span>
                                    </span>

                                    <span class="text-xs">Order ID: 
                                        <span class="text-dark ms-sm-2 font-weight-bold">
                                            {{$payment->id}}
                                        </span>
                                    </span>

                                </div>
                                <div class="ms-auto text-end">
                                    {{-- <a class="btn btn-link text-dark px-3 mb-0" href="{{route('Orders.ShopOrders.show' , $newestTransaction->id)}}"><i class="fa-solid fa-magnifying-glass text-dark me-2" aria-hidden="true"></i>Check</a> --}}
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                <div class="d-flex flex-column">
                                <h6 class="mb-3 text-sm">No Recent Payments</h6>
                                <span class="mb-2 text-xs">Product: <span class="text-dark font-weight-bold ms-sm-2">-</span></span>
                                <span class="mb-2 text-xs">Email Address: <span class="text-dark ms-sm-2 font-weight-bold">-</span></span>
                                <span class="text-xs">Date: <span class="text-dark ms-sm-2 font-weight-bold">{{ date('d M Y') }}</span></span>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

