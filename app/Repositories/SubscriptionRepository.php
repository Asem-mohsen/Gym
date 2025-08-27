<?php 
namespace App\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public function getAll(int $siteSettingId, $branchId = null)
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

        $subscriptions = $query->paginate(15);

        $counts = [
            'pending' => Subscription::where('status', 'pending')
                ->whereHas('user', function ($query) use ($siteSettingId)  {
                    $query->whereHas('gyms', function ($gymQuery) use ($siteSettingId) {
                        $gymQuery->where('site_setting_id', $siteSettingId);
                    });
                })
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->count(),

            'active'  => Subscription::where('status', 'active')
                ->whereHas('user', function ($query) use ($siteSettingId)  {
                    $query->whereHas('gyms', function ($gymQuery) use ($siteSettingId) {
                        $gymQuery->where('site_setting_id', $siteSettingId);
                    });
                })
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->count(),

            'expired' => Subscription::where('status', 'expired')
                ->whereHas('user', function ($query) use ($siteSettingId)  {
                    $query->whereHas('gyms', function ($gymQuery) use ($siteSettingId) {
                        $gymQuery->where('site_setting_id', $siteSettingId);
                    });
                })
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->count(),

            'total'   => Subscription::whereHas('user', function ($query) use ($siteSettingId)  {
                    $query->whereHas('gyms', function ($gymQuery) use ($siteSettingId) {
                        $gymQuery->where('site_setting_id', $siteSettingId);
                    });
                })
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->count(),
        ];

        return [
            'subscriptions' => $subscriptions,
            'counts' => $counts,
        ];
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
}