<?php 
namespace App\Services;

use App\Repositories\{ PaymentRepository , SubscriptionRepository };
use Exception;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function __construct(protected SubscriptionRepository $subscriptionRepository , protected PaymentRepository $paymentRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function getSubscriptions(int $siteSettingId, $branchId = null)
    {
        $data = $this->subscriptionRepository->getAll($siteSettingId, $branchId);
        return [$data['subscriptions'], $data['counts']];
    }

    public function showSubscription($subscriptionId)
    {
        return $this->subscriptionRepository->findById($subscriptionId);
    }

    public function getActiveSubscription(int $userId, int $siteSettingId)
    {
        return $this->subscriptionRepository->getActiveSubscription($userId,$siteSettingId);
    }

    public function createSubscription(array $data)
    {
        DB::beginTransaction();
    
        try {
            $subscription = $this->subscriptionRepository->createSubscription($data);
    
            if (!empty($data['offer_id'])) {
                $this->paymentRepository->createPayment($subscription, $data);
            }
    
            DB::commit();
            return $subscription;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error creating subscription: ' . $e->getMessage());
        }
    }
    
    public function updateSubscription($subscription, array $data)
    {
        DB::beginTransaction();
    
        try {
            $updatedSubscription = $this->subscriptionRepository->updateSubscription($subscription, $data);
    
            if (!empty($data['offer_id']) || !empty($data['amount'] )) {
                $existingPayment = $subscription->payment;

                if ($existingPayment) {
                    $this->paymentRepository->updatePayment($existingPayment, $subscription ,$data);
                } else {
                    $this->paymentRepository->createPayment($updatedSubscription, $data);
                }

            }

            DB::commit();
            return $updatedSubscription;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error updating subscription: ' . $e->getMessage());
        }
    }

    public function deleteSubscription($subscription)
    {
        return $this->subscriptionRepository->deleteSubscription($subscription);
    }
}