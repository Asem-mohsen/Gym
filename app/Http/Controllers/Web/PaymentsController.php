<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;

class PaymentsController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    )
    {}

    public function index()
    {
        $payments = $this->paymentService->getPayments();
        // dd($payments);
        return view('admin.payments.index' , get_defined_vars());
    }
}
