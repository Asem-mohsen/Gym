<?php

namespace App\Http\Controllers\Web\Admin;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\{User, Membership, Branch, ClassModel, Service, Booking, Checkin, Payment};
use App\Repositories\{ RoleRepository, UserRepository};
use App\Services\SiteSettingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(protected SiteSettingService $siteSettingService, protected RoleRepository $roleRepository, protected UserRepository $userRepository)
    {
        $this->siteSettingService = $siteSettingService;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        
        // Get basic metrics
        $basicMetrics = $this->getBasicMetrics($siteSettingId);
        
        // Get analytics data
        $analytics = $this->getAnalyticsData($siteSettingId);
        
        // Get financial data if user has permission
        $financialAnalytics = null;
        try {
            if (Auth::check() && method_exists(Auth::user(), 'can') && Auth::user()->can('view_financials')) {
                $financialAnalytics = $this->getFinancialAnalytics($siteSettingId);
            }
        } catch (Exception $e) {
            // If permission check fails, continue without financial data
            $financialAnalytics = null;
        }

        return view('admin.index', array_merge($basicMetrics, $analytics, [
            'financialAnalytics' => $financialAnalytics
        ]));
    }

    private function getBasicMetrics($siteSettingId)
    {
        // Get user counts by role
        $totalUsers = $this->userRepository->getUsersByRole('regular_user', $siteSettingId,true);

        $totalTrainers = $this->userRepository->getUsersByRole('trainer', $siteSettingId, true);

        $totalAdmins = $this->userRepository->getUsersByRole('admin', $siteSettingId, true);

        // Get basic counts
        $totalBranches = Branch::where('site_setting_id', $siteSettingId)->count();
        $totalClasses = ClassModel::where('site_setting_id', $siteSettingId)->count();
        $totalServices = Service::where('site_setting_id', $siteSettingId)->count();

        // Get subscribers count
        $totalSubscribers = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->count();

        return [
            'totalUsers' => $totalUsers,
            'totalTrainers' => $totalTrainers,
            'totalAdmins' => $totalAdmins,
            'totalBranches' => $totalBranches,
            'totalClasses' => $totalClasses,
            'totalServices' => $totalServices,
            'usersVsSubscribers' => [
                'users' => $totalUsers,
                'subscribers' => $totalSubscribers,
                'non_subscribers' => $totalUsers - $totalSubscribers
            ]
        ];
    }

    private function getAnalyticsData($siteSettingId)
    {
        return [
            'monthlyData' => $this->getMonthlyTrends($siteSettingId),
            'memberships' => $this->getMembershipData($siteSettingId),
            'classSubscriptions' => $this->getClassSubscriptionData($siteSettingId),
            'subscriptionStats' => $this->getSubscriptionStats($siteSettingId),
            'revenueData' => $this->getRevenueAnalytics($siteSettingId),
            'userGrowthData' => $this->getUserGrowthTrends($siteSettingId),
            'performanceMetrics' => $this->getPerformanceMetrics($siteSettingId),
            'membershipAnalytics' => $this->getMembershipAnalytics($siteSettingId),
            'attendanceAnalytics' => $this->getAttendanceAnalytics($siteSettingId),
            'trainerAnalytics' => $this->getTrainerAnalytics($siteSettingId),
            'retentionAnalytics' => $this->getRetentionAnalytics($siteSettingId)
        ];
    }

    private function getMembershipData($siteSettingId)
    {
        return Membership::where('site_setting_id', $siteSettingId)
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
    }

    private function getClassSubscriptionData($siteSettingId)
    {
        return ClassModel::where('site_setting_id', $siteSettingId)
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
    }

    private function getMonthlyTrends($siteSettingId)
    {
        $months = [];
        $subscriptions = [];
        $users = [];
        $revenue = [];

        // Get all data in one query for better performance
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Get subscription data with revenue
        $subscriptionData = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->join('memberships', 'bookings.bookable_id', '=', 'memberships.id')
            ->selectRaw('
                YEAR(bookings.created_at) as year,
                MONTH(bookings.created_at) as month,
                COUNT(*) as count,
                SUM(memberships.price) as revenue
            ')
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function($item) {
                return $item->year . '-' . $item->month;
            });

        // Get user data
        $userData = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })
        ->whereBetween('users.created_at', [$startDate, $endDate])
        ->selectRaw('
            YEAR(users.created_at) as year,
            MONTH(users.created_at) as month,
            COUNT(*) as count
        ')
        ->groupBy('year', 'month')
        ->get()
        ->keyBy(function($item) {
            return $item->year . '-' . $item->month;
        });

        // Build arrays for each month
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            $key = $month->year . '-' . $month->month;
            
            $subscriptions[] = $subscriptionData->get($key)->count ?? 0;
            $users[] = $userData->get($key)->count ?? 0;
            $revenue[] = $subscriptionData->get($key)->revenue ?? 0;
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

        // Get all subscription counts in one query
        $subscriptionCounts = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->selectRaw('
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_month,
                SUM(CASE WHEN created_at < ? THEN 1 ELSE 0 END) as expired
            ', [$currentMonth, $lastMonth, $currentMonth, $currentMonth])
            ->first();

        $activeSubscriptions = $subscriptionCounts->active ?? 0;
        $lastMonthSubscriptions = $subscriptionCounts->last_month ?? 0;
        $expiredSubscriptions = $subscriptionCounts->expired ?? 0;

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

        // Get revenue data in one query
        $revenueData = Booking::where('bookable_type', Membership::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->join('memberships', 'bookings.bookable_id', '=', 'memberships.id')
            ->selectRaw('
                SUM(CASE WHEN bookings.created_at >= ? THEN memberships.price ELSE 0 END) as current,
                SUM(CASE WHEN bookings.created_at BETWEEN ? AND ? THEN memberships.price ELSE 0 END) as last_month
            ', [$currentMonth, $lastMonth, $currentMonth])
            ->first();

        $currentRevenue = $revenueData->current ?? 0;
        $lastMonthRevenue = $revenueData->last_month ?? 0;
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

        // Get user growth data in one query
        $userData = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })
        ->selectRaw('
            SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as current,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_month
        ', [$currentMonth, $lastMonth, $currentMonth])
        ->first();

        $currentMonthUsers = $userData->current ?? 0;
        $lastMonthUsers = $userData->last_month ?? 0;
        $userGrowth = $lastMonthUsers > 0 ? (($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;

        return [
            'current' => $currentMonthUsers,
            'last_month' => $lastMonthUsers,
            'growth' => $userGrowth
        ];
    }

    private function getPerformanceMetrics($siteSettingId)
    {
        // Get performance metrics in optimized queries
        $classBookings = Booking::where('bookable_type', ClassModel::class)
            ->whereHas('user.gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('bookable', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->count();

        $totalClasses = ClassModel::where('site_setting_id', $siteSettingId)->count();
        $avgClassAttendance = $totalClasses > 0 ? $classBookings / $totalClasses : 0;

        // Get conversion rate data
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
        // Active vs Inactive Members - Based on last_visit_at column
        $activeMembers = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })
        ->where(function($query) {
            $query->where('last_visit_at', '>=', Carbon::now()->subMonth())
                  ->orWhereNotNull('last_visit_at');
        })
        ->count();

        $inactiveMembers = User::whereHas('gyms', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })
        ->where('last_visit_at', '<', Carbon::now()->subMonth())
        ->orWhereNull('last_visit_at')
        ->count();

        // New Signups per Month (Last 6 months) - Based on actual user creation
        $signupsPerMonth = [];
        $signupMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $signupMonths[] = $month->format('M Y');
            $signupsPerMonth[] = User::whereHas('roles', function($query) {
                $query->where('name', 'regular_user');
            })->whereHas('gyms', function($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereYear('users.created_at', $month->year)
            ->whereMonth('users.created_at', $month->month)
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
