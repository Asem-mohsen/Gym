<?php 
namespace App\Services;

use App\Repositories\PaymentRepository;

class PaymentService
{
    public function __construct(protected PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getPayments()
    {
        return $this->paymentRepository->getPayments();
    }


    public function updatePayment($payment, array $data)
    {
        return $this->paymentRepository->updatePayment($payment, $data);
    }

    public function showPayment($paymentId)
    {
        return $this->paymentRepository->findById($paymentId);
    }

    public function deletePayment($payment)
    {
        return $this->paymentRepository->deletePayment($payment);
    }
}
