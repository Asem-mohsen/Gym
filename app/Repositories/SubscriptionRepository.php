<?php 
namespace App\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public function getAll()
    {
        $subscriptions = Subscription::with(['user', 'membership', 'bookings'])->get();
    
        $counts = [
            'pending' => Subscription::where('status', 'pending')->count(),
            'active'  => Subscription::where('status', 'active')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
            'total'   => Subscription::count(),
        ];
    
        return [
            'subscriptions' => $subscriptions,
            'counts' => $counts,
        ];
    }
    

    public function createSubscription(array $data)
    {
        return Subscription::create($data);
    }

    public function updateSubscription(Subscription $subscription , array $data)
    {
        $subscription->update($data);
        return $subscription;
    }

    public function deleteSubscription(Subscription $subscription)
    {
        $subscription->delete();
    }

    public function findById(int $id): ?Subscription
    {
        return Subscription::with(['user' , 'membership', 'bookings'])->findOrFail($id);
    }
}