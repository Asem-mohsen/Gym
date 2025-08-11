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

    public function createPayment($paymentable, array $data)
    {
        return $this->paymentRepository->createPayment($paymentable, $data);
    }

    public function updatePayment($payment, $paymentable, array $data)
    {
        return $this->paymentRepository->updatePayment($payment, $paymentable, $data);
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
