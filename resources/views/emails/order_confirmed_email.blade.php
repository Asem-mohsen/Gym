@extends('layout.email')

@section('title','Order Confirmed')

@section('content')

    <td class="content-cell">
        <div class="f-fallback">
            <h1>Hi, {{ $userName }}!</h1>
            <p>Thank you for your order with {{ $appName }}. We’re processing your order and will let you know once it’s on its way.</p>

            <h2>Order Summary</h2>
            
                <h2>Order ID: {{ $order->id }}</h2>
                <p>Total Amount: {{ number_format($order->total_amount, 2) }}</p>
                <h3>Items:</h3>
                <table class="order-items" role="presentation" width="100%" style="border-collapse: collapse;">
                    @foreach($order->products as $orderProduct)
                        @php
                            $product = $orderProduct->product;
                        @endphp
                        <tr style="border-bottom: 1px solid #eee;">
                            <td width="15%">
                                <img src="{{ $product->image_url }}" alt="{{ $product->translations->name }}" style="max-width: 100%; border-radius: 4px;">
                            </td>
                            <td width="60%">
                                <p style="margin: 0;"><strong>{{ $product->translations->name }}</strong></p>
                                <p style="margin: 0; color: #555;">{{ $product->translations->description ?? ""}}</p>
                            </td>
                            <td width="10%" style="text-align: center;">
                                <p style="margin: 0;">Quantity: {{ $orderProduct->quantity }}</p>
                            </td>
                            <td width="15%" style="text-align: right;">
                                <p style="margin: 0;">Subtotal: {{ number_format($orderProduct->subtotal, 2) }}</p>
                            </td>
                        </tr>
                    @endforeach
                </table>

            <p>If you have any questions, feel free to reach out to us at <a href="mailto:{{ $supportMail }}">{{ $supportMail }}</a>.</p>

            <p>Thank you for choosing {{ $appName }}!</p>

            <!-- Sub copy -->
            <table class="body-sub" role="presentation">
                <tr>
                    <td>
                        <p class="f-fallback sub">
                            Best regards, <br>
                            The {{ $appName }} Team
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </td>
@endsection
