<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Membership, Branch, ClassModel, Service, Booking, Checkin, Machine, Payment, Subscription, Transaction, CoachingSession};
use App\Repositories\RoleRepository;
use App\Services\SiteSettingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        // 15. Membership & Users Analytics
        $membershipAnalytics = $this->getMembershipAnalytics($siteSettingId);

        // 16. Attendance & Check-ins Analytics
        $attendanceAnalytics = $this->getAttendanceAnalytics($siteSettingId);

        // 17. Trainers & Classes Analytics
        $trainerAnalytics = $this->getTrainerAnalytics($siteSettingId);

        // 18. Retention & Engagement Analytics
        $retentionAnalytics = $this->getRetentionAnalytics($siteSettingId);

        // 19. Financial Analytics (permission-based)
        $financialAnalytics = null;
        if (auth()->user()->can('view_financials')) {
            $financialAnalytics = $this->getFinancialAnalytics($siteSettingId);
        }

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
            'performanceMetrics',
            'membershipAnalytics',
            'attendanceAnalytics',
            'trainerAnalytics',
            'retentionAnalytics',
            'financialAnalytics'
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

    // New Methods for Enhanced Analytics

    private function getMembershipAnalytics($siteSettingId)
    {
        // Active vs Inactive Members
        $activeMembers = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('bookings.created_at', '>=', Carbon::now()->subMonth())
            ->distinct('user_id')
            ->count('user_id');

        $inactiveMembers = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('bookings.created_at', '<', Carbon::now()->subMonth())
            ->distinct('user_id')
            ->count('user_id');

        // New Signups per Month (Last 6 months)
        $signupsPerMonth = [];
        $signupMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $signupMonths[] = $month->format('M Y');
            $signupsPerMonth[] = Booking::where('bookable_type', Membership::class)
                ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                })
                ->whereHas('bookable', function($query) use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                })
                ->whereYear('bookings.created_at', $month->year)
                ->whereMonth('bookings.created_at', $month->month)
                ->count();
        }

        // Expiring Memberships (Next 30 days)
        $expiringMemberships = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereBetween('bookings.created_at', [
                Carbon::now()->subMonth()->subDays(30),
                Carbon::now()->subMonth()
            ])
            ->with(['user', 'bookable'])
            ->get()
            ->take(10);

        // Membership Plan Distribution
        $membershipDistribution = Membership::where('site_setting_id', $siteSettingId)
            ->withCount(['bookings as subscriber_count' => function($query) use ($siteSettingId) {
                $query->whereHas('user.gyms', function($q) use ($siteSettingId) {
                    $q->where('site_setting_id', $siteSettingId);
                });
            }])
            ->get()
            ->map(function($membership) {
                return [
                    'name' => $membership->name,
                    'count' => $membership->subscriber_count,
                    'percentage' => 0 // Will be calculated in view
                ];
            });

        // Calculate percentages
        $totalMembers = $membershipDistribution->sum('count');
        $membershipDistribution = $membershipDistribution->map(function($item) use ($totalMembers) {
            $item['percentage'] = $totalMembers > 0 ? round(($item['count'] / $totalMembers) * 100, 1) : 0;
            return $item;
        });

        return [
            'active_vs_inactive' => [
                'active' => $activeMembers,
                'inactive' => $inactiveMembers
            ],
            'signups_per_month' => [
                'months' => $signupMonths,
                'data' => $signupsPerMonth
            ],
            'expiring_memberships' => $expiringMemberships,
            'membership_distribution' => $membershipDistribution
        ];
    }

    private function getAttendanceAnalytics($siteSettingId)
    {
        // Daily Check-ins (Last 30 days)
        $dailyCheckins = [];
        $dailyDates = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyDates[] = $date->format('M d');
            $dailyCheckins[] = Checkin::where('site_setting_id', $siteSettingId)
                ->whereDate('created_at', $date)
                ->count();
        }

        // Peak Hours (by time of day)
        $peakHours = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $peakHours[] = Checkin::where('site_setting_id', $siteSettingId)
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count();
        }

        // Branch-wise Check-ins
        $branchCheckins = Branch::where('site_setting_id', $siteSettingId)
            ->withCount(['checkins' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            }])
            ->get()
            ->map(function($branch) {
                return [
                    'name' => $branch->name,
                    'checkins' => $branch->checkins_count
                ];
            });

        // Most Used Machines/Facilities (temporarily disabled)
        $machineUsage = collect([]);

        return [
            'daily_checkins' => [
                'dates' => $dailyDates,
                'data' => $dailyCheckins
            ],
            'peak_hours' => $peakHours,
            'branch_checkins' => $branchCheckins,
            'machine_usage' => $machineUsage
        ];
    }

    private function getTrainerAnalytics($siteSettingId)
    {
        // Trainer Sessions Count
        $trainerSessions = User::whereHas('roles', function($query) {
            $query->where('name', 'trainer');
        })
        ->whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })
        ->withCount(['coachingSessions' => function($query) {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        }])
        ->get()
        ->map(function($trainer) {
            return [
                'name' => $trainer->name,
                'sessions_count' => $trainer->coaching_sessions_count
            ];
        });

        // Top-rated Trainers (based on feedback - placeholder)
        $topRatedTrainers = User::whereHas('roles', function($query) {
            $query->where('name', 'trainer');
        })
        ->whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })
        ->withCount(['coachingSessions' => function($query) {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        }])
        ->orderBy('coaching_sessions_count', 'desc')
        ->take(5)
        ->get()
        ->map(function($trainer) {
            return [
                'name' => $trainer->name,
                'rating' => rand(4, 5) . '.' . rand(0, 9), // Placeholder rating
                'sessions' => $trainer->coaching_sessions_count
            ];
        });

        // Class Attendance Over Time
        $classAttendance = ClassModel::where('site_setting_id', $siteSettingId)
            ->withCount(['bookings as attendance_count' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonth());
            }])
            ->get()
            ->map(function($class) {
                return [
                    'name' => $class->name,
                    'attendance' => $class->attendance_count
                ];
            });

        // Class Occupancy Rate
        $classOccupancy = ClassModel::where('site_setting_id', $siteSettingId)
            ->withCount(['bookings as booked_count' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonth());
            }])
            ->get()
            ->map(function($class) {
                $capacity = 20; // Default capacity - should come from class model
                $occupancyRate = $capacity > 0 ? round(($class->booked_count / $capacity) * 100, 1) : 0;
                return [
                    'name' => $class->name,
                    'occupancy_rate' => $occupancyRate,
                    'booked' => $class->booked_count,
                    'capacity' => $capacity
                ];
            });

        return [
            'trainer_sessions' => $trainerSessions,
            'top_rated_trainers' => $topRatedTrainers,
            'class_attendance' => $classAttendance,
            'class_occupancy' => $classOccupancy
        ];
    }

    private function getRetentionAnalytics($siteSettingId)
    {
        // Churn Rate (Last 3 months) - Simplified calculation
        $churnRate = [];
        $churnMonths = [];
        for ($i = 2; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $churnMonths[] = $month->format('M Y');
            
            // For now, use a simplified churn calculation
            // This can be enhanced later with more sophisticated logic
            $monthlyBookings = Booking::where('bookable_type', Membership::class)
                ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                })
                ->whereHas('bookable', function($query) use ($siteSettingId) {
                    $query->where('site_setting_id', $siteSettingId);
                })
                ->whereYear('bookings.created_at', $month->year)
                ->whereMonth('bookings.created_at', $month->month)
                ->count();

            // Calculate a simple churn rate based on monthly activity
            // This is a placeholder - can be enhanced with actual renewal logic
            $churnRate[] = $monthlyBookings > 0 ? round(rand(5, 15), 1) : 0; // Placeholder data
        }

        // Average Membership Duration
        $avgMembershipDuration = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->where('bookings.created_at', '>=', Carbon::now()->subYear())
            ->get()
            ->avg(function($booking) {
                return $booking->created_at->diffInDays(Carbon::now());
            });

        return [
            'churn_rate' => [
                'months' => $churnMonths,
                'data' => $churnRate
            ],
            'avg_membership_duration' => round($avgMembershipDuration ?? 0, 1)
        ];
    }

    private function getFinancialAnalytics($siteSettingId)
    {
        // Monthly Revenue (Last 12 months)
        $monthlyRevenue = [];
        $revenueMonths = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenueMonths[] = $month->format('M Y');
            $monthlyRevenue[] = Booking::where('bookable_type', Membership::class)
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

        // Revenue by Membership Plan
        $revenueByPlan = Membership::where('site_setting_id', $siteSettingId)
            ->withSum(['bookings as total_revenue' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonth());
            }], 'amount')
            ->get()
            ->map(function($membership) {
                return [
                    'name' => $membership->name,
                    'revenue' => $membership->total_revenue ?? 0
                ];
            });

        // Revenue by Branch
        $revenueByBranch = Branch::where('site_setting_id', $siteSettingId)
            ->withSum(['bookings as total_revenue' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonth());
            }], 'amount')
            ->get()
            ->map(function($branch) {
                return [
                    'name' => $branch->name,
                    'revenue' => $branch->total_revenue ?? 0
                ];
            });

        // Outstanding Payments
        $outstandingPayments = Payment::where('site_setting_id', $siteSettingId)
            ->where('status', 'pending')
            ->sum('amount');

        return [
            'monthly_revenue' => [
                'months' => $revenueMonths,
                'data' => $monthlyRevenue
            ],
            'revenue_by_plan' => $revenueByPlan,
            'revenue_by_branch' => $revenueByBranch,
            'outstanding_payments' => $outstandingPayments
        ];
    }
}
