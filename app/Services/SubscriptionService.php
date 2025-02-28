<?php 
namespace App\Services;

use App\Repositories\SubscriptionRepository;
use Illuminate\Support\Facades\Hash;

class SubscriptionService
{
    public function __construct(protected SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function getSubscriptions()
    {
        $data = $this->subscriptionRepository->getAll();
        return [$data['subscriptions'], $data['counts']];
    }

    public function showSubscription($subscriptionId)
    {
        return $this->subscriptionRepository->findById($subscriptionId);
    }

    public function createSubscription(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = 0 ;
        return $this->subscriptionRepository->createSubscription($data);
    }

    public function updateSubscription($subscription, array $data)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->subscriptionRepository->updateSubscription($subscription, $data);
    }

    public function deleteSubscription($subscription)
    {
        return $this->subscriptionRepository->deleteSubscription($subscription);
    }
}