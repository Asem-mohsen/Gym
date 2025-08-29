<?php 
namespace App\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public function getAll(int $siteSettingId, $branchId = null, $status = null, $membershipId = null, $dateFrom = null, $dateTo = null)
    {
        $query = Subscription::with(['user', 'membership', 'bookings'])
            ->whereHas('user', function ($query) use ($siteSettingId)  {
                $query->whereHas('gyms', function ($gymQuery) use ($siteSettingId) {
                    $gymQuery->where('site_setting_id', $siteSettingId);
                });
            });

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        // Filter by status
        if ($status) {
            if ($status === 'about_to_expire') {
                // Subscriptions expiring within 30 days
                $query->where('status', 'active')
                      ->where('end_date', '>=', now()->toDateString())
                      ->where('end_date', '<=', now()->addDays(30)->toDateString());
            } elseif ($status === 'expired') {
                // Expired subscriptions
                $query->where(function($q) {
                    $q->where('status', 'expired')
                      ->orWhere('end_date', '<', now()->toDateString());
                });
            } else {
                $query->where('status', $status);
            }
        }

        // Filter by membership type
        if ($membershipId) {
            $query->where('membership_id', $membershipId);
        }

        // Filter by date range
        if ($dateFrom) {
            $query->where('start_date', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->where('end_date', '<=', $dateTo);
        }

        $subscriptions = $query->paginate(15);

        return $subscriptions;
    }

    public function getActiveSubscription(int $userId, int $siteSettingId)
    {
        return Subscription::where('user_id', $userId)
            ->whereHas('branch', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->first();
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
        return Subscription::with(['user' , 'membership.payment.offer', 'bookings' , 'branch'])->findOrFail($id);
    }

    public function updateExpiredSubscriptions()
    {
        $expiredSubscriptions = Subscription::where('status', 'active')
            ->where('end_date', '<', now()->toDateString())
            ->get();

        $updatedCount = 0;

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update(['status' => 'expired']);
            $updatedCount++;
        }

        return $updatedCount;
    }
}