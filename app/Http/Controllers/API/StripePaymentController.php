<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Http\Requests\Payments\StoreRequest;
use App\Models\Payments;

class StripePaymentController extends Controller
{

    public function store(StoreRequest $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        // Stripe::setApiKey(config('services.stripe.secret'));
        $YOUR_DOMAIN = 'http://localhost:4200';

        try {
            $checkout_session = StripeSession::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'EGP',
                        'product_data' => [
                            'name' => 'Cart Purchase',
                        ],
                        'unit_amount' => $request->cost * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/',
                'cancel_url' => $YOUR_DOMAIN . '/',
            ]);

            return successResponse(['url' => $checkout_session->url] , 'user will be redirected to payment gatway');

        } catch (Exception $e) {
            return failureResponse(['error' => $e->getMessage()] , 'An error occured please try again' );
        }

    }
}
