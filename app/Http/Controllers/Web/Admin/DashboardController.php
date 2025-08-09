<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Subscription, Membership, Branch, ClassModel, Service, Role, Booking};
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(protected SiteSettingService $siteSettingService)
    {
        $this->siteSettingService = $siteSettingService;
    }

    public function index()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();

        // 1. Total number of users in the gym
        $totalUsers = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->count();

        // 2. Total number of trainers
        $trainerRole = Role::where('name', 'Trainer')->first();
        $totalTrainers = User::where('role_id', $trainerRole?->id ?? 0)->count();

        // 3. Total number of admins
        $adminRole = Role::where('name', 'Admin')->first();
        $totalAdmins = User::where('role_id', $adminRole?->id ?? 0)->count();

        // 4. Chart of number of users to subscribers (using bookings for memberships)
        $totalSubscribers = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->count();

        $usersVsSubscribers = [
            'users' => $totalUsers,
            'subscribers' => $totalSubscribers,
            'non_subscribers' => $totalUsers - $totalSubscribers
        ];

        // 5. Chart of subscriptions per month (last 12 months) - using bookings for memberships
        $subscriptionsPerMonth = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                    'count' => $item->count
                ];
            });

        // 6. Number of memberships and users subscribed to each (using bookings)
        $memberships = Membership::where('site_setting_id', $siteSettingId)
            ->withCount(['bookings as subscriber_count' => function($query) use ($siteSettingId) {
                $query->whereHas('user.gyms', function($q) use ($siteSettingId) {
                    $q->where('site_setting_id', $siteSettingId);
                });
            }])
            ->get()
            ->map(function($membership) {
                return [
                    'name' => $membership->name,
                    'subscriber_count' => $membership->subscriber_count,
                    'price' => $membership->price
                ];
            });

        // 7. Total number of branches
        $totalBranches = Branch::where('site_setting_id', $siteSettingId)->count();

        // 8. Total number of classes
        $totalClasses = ClassModel::where('site_setting_id', $siteSettingId)->count();

        // 9. Total number of services
        $totalServices = Service::where('site_setting_id', $siteSettingId)->count();

        // 10. Chart of users subscribed to classes (bookings for classes)
        $classSubscriptions = ClassModel::where('site_setting_id', $siteSettingId)
            ->withCount(['bookings as subscriber_count' => function($query) use ($siteSettingId) {
                $query->whereHas('user.gyms', function($q) use ($siteSettingId) {
                    $q->where('site_setting_id', $siteSettingId);
                });
            }])
            ->get()
            ->map(function($class) {
                return [
                    'name' => $class->name,
                    'subscriber_count' => $class->subscriber_count
                ];
            });

        // Additional statistics - using bookings for memberships
        $activeSubscriptions = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        $expiredSubscriptions = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->count();

        return view('admin.index', compact(
            'totalUsers',
            'totalTrainers', 
            'totalAdmins',
            'usersVsSubscribers',
            'subscriptionsPerMonth',
            'memberships',
            'totalBranches',
            'totalClasses',
            'totalServices',
            'classSubscriptions',
            'activeSubscriptions',
            'expiredSubscriptions'
        ));
    }
}
