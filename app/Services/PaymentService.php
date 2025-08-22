<?php 
namespace App\Services;

use App\Repositories\PaymentRepository;
use App\Services\SiteSettingService;

class PaymentService
{
    protected int $siteSettingId;

    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected SiteSettingService $siteSettingService
    )
    {
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function getPayments()
    {
        return $this->paymentRepository->getPayments($this->siteSettingId);
    }

    public function createPayment($paymentable, array $data)
    {
        return $this->paymentRepository->createPayment($paymentable, $data);
    }

    public function updatePayment($payment, $paymentable, array $data)
    {
        return $this->paymentRepository->updatePayment($payment, $paymentable, $data);
    }
}
