@extends('layout.master')

@section('title' , 'Order')

@section('content')

    <div class="container my-5">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary justify-content-center" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#order" aria-selected="true">Order Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#User" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#Product" aria-selected="false">Product Information</a>
                    </li>
                    @if ($order->user)
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#Billing" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Billing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#PrevOrders" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Previous Orders</a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content py-3">
                    <!-- Order -->
                    <div class="tab-pane fade show active" id="order" role="tabpanel">
                        <div class="wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-striped table-bordered">
                                            <thead>
                                                <th scope="col" width="10%">#</th>
                                                <th scope="col" width="20%">Data</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>User</td>
                                                    <td>{{$order->user->name ?? "Unkown User"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Number of Products</td>
                                                    <td>{{$order->products->count()}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Placed Date</td>
                                                    <td>{{$order->created_at }}</td>
                                                </tr>
                                                @if ($order->promotions->isNotEmpty())
                                                    @foreach ($order->promotions as $promotion)
                                                        <tr>
                                                            <td>Promocode</td>
                                                            <td>
                                                                {{$promotion->code }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>The Code Save</td>
                                                            <td>{{$promotions->discount_amount . "%" }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total</td>
                                                            <td>{{$order->total_amount . " EGP" }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>Total</td>
                                                        <td>{{$order->total_amount . " EGP" }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User -->
                    <div class="tab-pane fade" id="User" role="tabpanel">
                        <div class="wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-striped table-bordered">
                                            <thead>
                                                <th scope="col" width="10%">#</th>
                                                <th scope="col" width="20%">Data</th>
                                            </thead>
                                                <tr>
                                                    <td>User</td>
                                                    <td>{{$order->user->name ?? "Unkown User"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td>{{$order->user->email ?? "Unkown email"}}</td>
                                                </tr>
                                                @forelse($order->user->phones->take(2) as $phone)
                                                    <tr>
                                                        <td>Phone</td>
                                                        <td>{{$phone->phone}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td>Phone</td>
                                                        <td>-</td>
                                                    </tr>
                                                @endforelse
                                                <tr>
                                                    <td>User Address</td>
                                                    <td>{{$order->user->address->street_address}}</td>
                                                </tr>
                                                    <tr>
                                                    <td>City</td>
                                                    <td>{{$order->user->address->city->name}}</td>
                                                </tr>
                                                    <tr>
                                                    <td>Country</td>
                                                    <td>{{$order->user->address->country->country}}</td>
                                                </tr>
                                                    <tr>
                                                    <td>Building</td>
                                                    <td>{{$order->user->address->building}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Floor</td>
                                                    <td>{{$order->user->address->floor}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Apartment</td>
                                                    <td>{{$order->user->address->apartment}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>
                                                        @if ($order->user->status)
                                                            <span class="badge badge-sm bg-success">Active</span>
                                                        @else
                                                            <span class="badge badge-sm bg-danger">Not Active</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product -->
                    <div class="tab-pane fade" id="Product" role="tabpanel">
                        <div class="wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width="5%">#</th>
                                                    <th scope="col" width="20%">Product</th>
                                                    <th scope="col" width="20%">Quantity</th>
                                                    <th scope="col" width="20%">Category</th>
                                                    <th scope="col" width="20%">Price</th>
                                                    <th scope="col" width="15%">Stock</th>
                                                    <th scope="col" width="20%">Platform</th>
                                                    <th scope="col" width="20%">Brand</th>
                                                    <th scope="col" width="10%">Bundle</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->products as $index => $orderProduct)
                                                    @php
                                                        $product = $orderProduct->product;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $product->translations->name }}</td>
                                                        <td>{{ $orderProduct->quantity }}</td>
                                                        <td>
                                                            <div class="d-flex px-2 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0">{{ $product->subcategory->category->name }}</h6>
                                                                    <span class="text-sm">{{ $product->subcategory->name }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($product->isOnSale())
                                                                <div>
                                                                    <span class="text-decoration-line-through">
                                                                        {{ $product->price . ' EGP' }}
                                                                    </span>
                                                                    <br>
                                                                    <span>
                                                                        {{ $product->sale->sale_price . ' EGP' }}
                                                                    </span>
                                                                </div>
                                                            @else
                                                                {{ $product->price . ' EGP' }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $product->quantity }}</td>
                                                        <td class="Platforms-orders">
                                                            <div class="d-flex gap-2 align-items-center">
                                                                @foreach($product->platforms as $platform)
                                                                    <a href="{{ route('Platform.edit', $platform->id) }}">
                                                                        <div class="platform d-flex align-items-center border pr-1 rounded">
                                                                            <img src="{{ $platform->getFirstMediaUrl('platform-image') }}" width="60" alt="{{ $platform->name }}">
                                                                            <p class="text-black m-0">{{ $platform->name }}</p>
                                                                        </div>
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td>{{ $product->brand->name }}</td>
                                                        <td>
                                                            @if ($product->is_bundle)
                                                                Bundle
                                                            @else
                                                                Not a Bundle
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Billing -->
                    <div class="tab-pane fade" id="Billing" role="tabpanel">
                        <div class="wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Data</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>User</td>
                                                    <td>{{$order->user->name ?? "Unkown User"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Amount</td>
                                                    <td>{{$order->total_amount . " EGP"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Payment Type</td>
                                                    @if ($order->transaction_id != NULL)
                                                        <td>{{$order->payment->type}}</td>
                                                    @else
                                                        <td>
                                                            <span class="badge badge-sm bg-warning">Pending</span>
                                                        </td>
                                                    @endif

                                                </tr>
                                                <tr>
                                                    @if ($order->transaction_id != NULL)
                                                        <td>Transaction ID </td>
                                                        <td>{{$order->transaction_id}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>
                                                        @if ($order->status === \App\Enums\OrderStatusEnum::COMPLETED->value)
                                                            <span class="badge badge-success">Paid</span>
                                                        @elseif ($order->status === \App\Enums\OrderStatusEnum::PENDING->value)
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif ($order->status === \App\Enums\OrderStatusEnum::CANCELLED->value)
                                                            <span class="badge badge-danger">Cancelled</span>
                                                        @elseif ($order->status === \App\Enums\OrderStatusEnum::REFUNDED->value)
                                                            <span class="badge badge-info">Refunded</span>
                                                        @elseif ($order->status === \App\Enums\OrderStatusEnum::CASH_ON_DELIVERY->value)
                                                            <span class="badge badge-success">Cash on delivery</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if ($order->promotions->isNotEmpty())
                                                    @foreach ($order->promotions as $promotion)
                                                        <tr>
                                                            <td>Promocode</td>
                                                            <td>
                                                                {{$promotion->code }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>The Code Save</td>
                                                            <td>{{$promotion->discount_amount . "%" }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>Total</td>
                                                        <td>{{$order->total_amount . " EGP" }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Previous Orders -->
                    <div class="tab-pane fade" id="PrevOrders" role="tabpanel">
                        <div class="wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <h3 class="align-middle text-center text-lg font-weight-bold">
                                            Previous Orders
                                        </h3>
                                        <table id="PrevOrder" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>OrderID</th>
                                                    <th>Num of products</td>
                                                    <th>Paid With</th>
                                                    <th>Promocode</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($previousOrders as $prevOrder)
                                                    <tr>
                                                        <td class="align-middle text-center">
                                                            {{$prevOrder->id}}
                                                        </td>
                                                        <td>
                                                            {{$prevOrder->products->count()}}
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            @if (!empty($prevOrder->transaction_id))
                                                                {{-- <td>{{$prevOrder->transaction->source_data_sub_type}}</td> --}}
                                                            @else
                                                                <span class="badge badge-sm bg-warning">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            @if (!empty($prevOrder->promotions))
                                                                @foreach ($prevOrder->promotions as $promotion)
                                                                    <p class="text-xs font-weight-bold mb-0">{{ $promotion->code ?? 'No code' }}</p>
                                                                @endforeach
                                                            @else
                                                                <p class="text-xs font-weight-bold mb-0">No promo code used</p>
                                                            @endif
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            @if ($order->status === \App\Enums\OrderStatusEnum::COMPLETED->value)
                                                                <span class="badge badge-success">Paid</span>
                                                            @elseif ($order->status === \App\Enums\OrderStatusEnum::PENDING->value)
                                                                <span class="badge badge-warning">Pending</span>
                                                            @elseif ($order->status === \App\Enums\OrderStatusEnum::CANCELLED->value)
                                                                <span class="badge badge-danger">Cancelled</span>
                                                            @elseif ($order->status === \App\Enums\OrderStatusEnum::REFUNDED->value)
                                                                <span class="badge badge-info">Refunded</span>
                                                            @elseif ($order->status === \App\Enums\OrderStatusEnum::CASH_ON_DELIVERY->value)
                                                                <span class="badge badge-success">Cash on delivery</span>
                                                            @endif
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            {{$prevOrder->total_amount . " EGP"}}
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <a href="{{route('Orders.ShopOrders.show' , $prevOrder->id)}}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Check">
                                                                Check
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('Js')
    <!-- Page specific script -->
    <script>
        $(function () {
            $("#PrevOrder").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@stop
