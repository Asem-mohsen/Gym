<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Membership, Branch, ClassModel, Service, Booking};
use App\Repositories\RoleRepository;
use App\Services\SiteSettingService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(protected SiteSettingService $siteSettingService, protected RoleRepository $roleRepository)
    {
        $this->siteSettingService = $siteSettingService;
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();

        // 1. Total number of users in the gym
        $totalUsers = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->count();

        // 2. Total number of trainers
        $totalTrainers = $this->roleRepository->getRoleByName('trainer')->count();

        // 3. Total number of admins
        $totalAdmins = $this->roleRepository->getRoleByName('admin')->count();

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

        // 5. Enhanced monthly trends for the last 12 months
        $monthlyData = $this->getMonthlyTrends($siteSettingId);

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

        // 11. Enhanced subscription statistics
        $subscriptionStats = $this->getSubscriptionStats($siteSettingId);

        // 12. Revenue analytics
        $revenueData = $this->getRevenueAnalytics($siteSettingId);

        // 13. User growth trends
        $userGrowthData = $this->getUserGrowthTrends($siteSettingId);

        // 14. Performance metrics
        $performanceMetrics = $this->getPerformanceMetrics($siteSettingId);

        return view('admin.index', compact(
            'totalUsers',
            'totalTrainers', 
            'totalAdmins',
            'usersVsSubscribers',
            'monthlyData',
            'memberships',
            'totalBranches',
            'totalClasses',
            'totalServices',
            'classSubscriptions',
            'subscriptionStats',
            'revenueData',
            'userGrowthData',
            'performanceMetrics'
        ));
    }

    private function getMonthlyTrends($siteSettingId)
    {
        $months = [];
        $subscriptions = [];
        $users = [];
        $revenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            // Subscriptions
            $subscriptions[] = Booking::where('bookable_type', Membership::class)
                ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                })
                ->whereHas('bookable', function($query) use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                })
                ->whereYear('bookings.created_at', $month->year)
                ->whereMonth('bookings.created_at', $month->month)
                ->count();
            
            // New users
            $users[] = User::whereHas('gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereYear('users.created_at', $month->year)
            ->whereMonth('users.created_at', $month->month)
            ->count();
            
            // Revenue
            $revenue[] = Booking::where('bookable_type', Membership::class)
                ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                })
                ->whereHas('bookable', function($query) use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                })
                ->whereYear('bookings.created_at', $month->year)
                ->whereMonth('bookings.created_at', $month->month)
                ->join('memberships', 'bookings.bookable_id', '=', 'memberships.id')
                ->sum('memberships.price');
        }

        return [
            'months' => $months,
            'subscriptions' => $subscriptions,
            'users' => $users,
            'revenue' => $revenue
        ];
    }

    private function getSubscriptionStats($siteSettingId)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $activeSubscriptions = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('bookings.created_at', '>=', $currentMonth)
            ->count();

        $lastMonthSubscriptions = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereBetween('bookings.created_at', [$lastMonth, $currentMonth])
            ->count();

        $expiredSubscriptions = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('bookings.created_at', '<', $currentMonth)
            ->count();

        $growthRate = $lastMonthSubscriptions > 0 ? (($activeSubscriptions - $lastMonthSubscriptions) / $lastMonthSubscriptions) * 100 : 0;

        return [
            'active' => $activeSubscriptions,
            'expired' => $expiredSubscriptions,
            'growth_rate' => $growthRate,
            'last_month' => $lastMonthSubscriptions
        ];
    }

    private function getRevenueAnalytics($siteSettingId)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentRevenue = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('bookings.created_at', '>=', $currentMonth)
            ->join('memberships', 'bookings.bookable_id', '=', 'memberships.id')
            ->sum('memberships.price');

        $lastMonthRevenue = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereBetween('bookings.created_at', [$lastMonth, $currentMonth])
            ->join('memberships', 'bookings.bookable_id', '=', 'memberships.id')
            ->sum('memberships.price');

        $revenueGrowth = $lastMonthRevenue > 0 ? (($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        return [
            'current' => $currentRevenue,
            'last_month' => $lastMonthRevenue,
            'growth' => $revenueGrowth
        ];
    }

    private function getUserGrowthTrends($siteSettingId)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthUsers = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->where('users.created_at', '>=', $currentMonth)->count();

        $lastMonthUsers = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->whereBetween('users.created_at', [$lastMonth, $currentMonth])->count();

        $userGrowth = $lastMonthUsers > 0 ? (($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;

        return [
            'current' => $currentMonthUsers,
            'last_month' => $lastMonthUsers,
            'growth' => $userGrowth
        ];
    }

    private function getPerformanceMetrics($siteSettingId)
    {
        // Average class attendance
        $avgClassAttendance = Booking::where('bookable_type', ClassModel::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->count() / max(ClassModel::where('site_setting_id', $siteSettingId)->count(), 1);

        // Membership conversion rate
        $totalUsers = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->count();

        $membershipUsers = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->distinct('user_id')
            ->count('user_id');

        $conversionRate = $totalUsers > 0 ? ($membershipUsers / $totalUsers) * 100 : 0;

        return [
            'avg_class_attendance' => round($avgClassAttendance, 1),
            'conversion_rate' => round($conversionRate, 1)
        ];
    }
}
