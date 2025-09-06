@extends('layout.admin.master')

@section('title','Dashboard')

@section('page-title', 'Dashboard')

@section('main-breadcrumb', 'Dashboard')

@section('sub-breadcrumb','Dashboard')

@section('content')

<!-- Clean Dashboard Layout -->
<div class="row g-3 mb-4">
    <!-- Key Metrics -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="fs-6 text-muted mb-1">Total Users</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($totalUsers) }}</div>
                        <div class="fs-7 text-muted">+{{ number_format($userGrowthData['growth'], 1) }}% vs last month</div>
                    </div>
                    <div class="symbol symbol-40px">
                        <i class="ki-duotone ki-profile-user fs-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="fs-6 text-muted mb-1">Active Members</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($membershipAnalytics['active_vs_inactive']['active']) }}</div>
                        <div class="fs-7 text-muted">{{ number_format($membershipAnalytics['active_vs_inactive']['inactive']) }} inactive</div>
                    </div>
                    <div class="symbol symbol-40px">
                        <i class="ki-duotone ki-profile-circle fs-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="fs-6 text-muted mb-1">Active Subscriptions</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($subscriptionStats['active']) }}</div>
                        <div class="fs-7 text-muted">+{{ number_format($subscriptionStats['growth_rate'], 1) }}% vs last month</div>
                    </div>
                    <div class="symbol symbol-40px">
                        <i class="ki-duotone ki-check-circle fs-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="fs-6 text-muted mb-1">Total Trainers</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($totalTrainers) }}</div>
                        <div class="fs-7 text-muted">Professional trainers</div>
                    </div>
                    <div class="symbol symbol-40px">
                        <i class="ki-duotone ki-profile-circle fs-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Metrics -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="fs-6 text-muted mb-1">Avg Class Attendance</div>
                        <div class="fs-2 fw-bold text-dark">{{ $performanceMetrics['avg_class_attendance'] }}</div>
                        <div class="fs-7 text-muted">Per class average</div>
                    </div>
                    <div class="symbol symbol-40px">
                        <i class="ki-duotone ki-calendar fs-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="fs-6 text-muted mb-1">Total Branches</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($totalBranches) }}</div>
                        <div class="fs-7 text-muted">Gym locations</div>
                    </div>
                    <div class="symbol symbol-40px">
                        <i class="ki-duotone ki-geolocation fs-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="fs-6 text-muted mb-1">Total Classes</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($totalClasses) }}</div>
                        <div class="fs-7 text-muted">Active classes</div>
                    </div>
                    <div class="symbol symbol-40px">
                        <i class="ki-duotone ki-calendar fs-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="fs-6 text-muted mb-1">Conversion Rate</div>
                        <div class="fs-2 fw-bold text-dark">{{ $performanceMetrics['conversion_rate'] }}%</div>
                        <div class="fs-7 text-muted">User to member</div>
                    </div>
                    <div class="symbol symbol-40px">
                        <i class="ki-duotone ki-chart-simple fs-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-3 mb-4">
    <!-- Monthly Trends Chart -->
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Monthly Trends</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Revenue, Users & Subscriptions (Last 12 Months)</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows monthly trends for revenue, new users, and subscription growth over the past 12 months">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="monthlyTrendsChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Users vs Subscribers Chart -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">User Distribution</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Subscribers vs Non-Subscribers</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows the percentage breakdown of users who have active subscriptions versus those who don't">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="userDistributionChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Membership and Class Analytics -->
<div class="row g-3 mb-4">
    <!-- Membership Analytics -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Membership Analytics</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Subscribers per membership type</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows the number of subscribers for each membership plan type">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="membershipAnalyticsChart" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- Class Analytics -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Class Analytics</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Subscribers per class</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows the number of subscribers for each class type">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="classAnalyticsChart" style="height: 250px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Breakdown -->
<div class="row g-3 mb-4">
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Revenue Breakdown</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Monthly revenue trends</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows monthly revenue trends over the past 12 months">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="revenueBreakdownChart" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Performance Overview</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Key performance indicators</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows key performance metrics including conversion rates and revenue growth">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="fs-7 text-muted fw-semibold">Conversion Rate</span>
                            <span class="fs-3 fw-bold text-dark">{{ $performanceMetrics['conversion_rate'] }}%</span>
                            <div class="progress h-3px bg-light mt-2">
                                <div class="progress-bar bg-dark" style="width: {{ $performanceMetrics['conversion_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="fs-7 text-muted fw-semibold">Avg Attendance</span>
                            <span class="fs-3 fw-bold text-dark">{{ $performanceMetrics['avg_class_attendance'] }}</span>
                            <span class="fs-8 text-muted">per class</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="fs-7 text-muted fw-semibold">Active Subscriptions</span>
                            <span class="fs-3 fw-bold text-dark">{{ number_format($subscriptionStats['active']) }}</span>
                            <span class="fs-8 text-muted">+{{ number_format($subscriptionStats['growth_rate'], 1) }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="fs-7 text-muted fw-semibold">Monthly Revenue</span>
                            <span class="fs-3 fw-bold text-dark">${{ number_format($revenueData['current']) }}</span>
                            <span class="fs-8 text-muted">+{{ number_format($revenueData['growth'], 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Membership Analytics -->
<div class="row g-3 mb-4">
    <!-- Active vs Inactive Members Chart -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Active vs Inactive Members</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Member activity status</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows the distribution of active versus inactive members based on recent activity">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="activeInactiveChart" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- New Signups per Month -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">New Signups per Month</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Last 6 months trend</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows the number of new member signups over the past 6 months">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="signupsPerMonthChart" style="height: 250px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Membership Plan Distribution & Expiring Memberships -->
<div class="row g-3 mb-4">
    <!-- Membership Plan Distribution -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Membership Plan Distribution</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Subscribers per plan type</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows the distribution of subscribers across different membership plan types">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="membershipPlanChart" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- Expiring Memberships -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Expiring Memberships</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Next 30 days</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Lists memberships that will expire in the next 30 days for renewal follow-up">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-2">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-150px">Member</th>
                                <th class="min-w-140px">Plan</th>
                                <th class="min-w-120px">Expires</th>
                                <th class="min-w-100px text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($membershipAnalytics['expiring_memberships'] as $membership)
                            <tr>
                                <td class="text-dark fw-bold text-hover-primary fs-7">{{ $membership->user->name ?? 'N/A' }}</td>
                                <td class="text-dark fw-bold text-hover-primary fs-7">{{ $membership->bookable->name ?? 'N/A' }}</td>
                                <td class="text-dark fw-bold text-hover-primary fs-7">{{ $membership->created_at->addMonth()->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                        <i class="ki-duotone ki-message-text-2 fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted fs-7">No expiring memberships</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Analytics -->
<div class="row g-3 mb-4">
    <!-- Daily Check-ins -->
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Daily Check-ins</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Last 30 days attendance</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows daily gym check-ins over the past 30 days to track attendance patterns">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="dailyCheckinsChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Peak Hours -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Peak Hours</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Busiest times of day</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows the busiest hours of the day based on check-in patterns to help with staffing and capacity planning">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="peakHoursChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Branch Check-ins -->
<div class="row g-3 mb-4">
    <!-- Branch-wise Check-ins -->
    <div class="col-xl-12">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Branch-wise Check-ins</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Last 30 days</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows check-in distribution across different gym branches to compare branch performance">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="branchCheckinsChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Churn Rate Analytics -->
<div class="row g-3 mb-4">
    <!-- Churn Rate -->
    <div class="col-xl-12">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Churn Rate</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Last 3 months trend</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Churn rate measures the percentage of members who cancel their subscriptions. A lower churn rate indicates better member retention and satisfaction">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="churnRateChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>


@if($financialAnalytics)
<!-- Financial Analytics -->
<div class="row g-3 mb-4">
    <!-- Monthly Revenue -->
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Monthly Revenue</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Last 12 months trend</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows monthly revenue trends over the past 12 months to track financial performance">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="monthlyRevenueChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Revenue by Branch -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header border-0 pt-4">
                <div class="card-title d-flex align-items-center">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-5 mb-1">Revenue by Branch</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">This month performance</span>
                    </h3>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-icon btn-color-gray-400 btn-active-color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Shows revenue breakdown by branch to compare performance across locations">
                            <i class="ki-duotone ki-information-5 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="revenueByBranchChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('css')
<style>
/* Clean Dashboard Styles */
.card {
    border: 1px solid #e4e6ea;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e4e6ea;
}

.symbol {
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fs-8 {
    font-size: 0.75rem !important;
}

/* Chart styling */
.card-body {
    padding: 1rem;
}

/* Table improvements */
.table {
    font-size: 0.875rem;
}

.table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.8rem;
}

.table td {
    border: none;
    border-bottom: 1px solid #f1f3f4;
    font-size: 0.8rem;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .fs-2 {
        font-size: 1.5rem !important;
    }
    
    .symbol-40px {
        width: 32px !important;
        height: 32px !important;
    }
    
    .card-body {
        padding: 0.75rem;
    }
}
</style>
@endsection

@section('js')
<!-- Highcharts Scripts -->
<script src="{{ asset('assets/admin/plugins/custom/highcharts/highcharts.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/highcharts/highcharts-more.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/highcharts/modules/exporting.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/highcharts/modules/export-data.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/highcharts/modules/accessibility.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    // Monthly Trends Chart
    Highcharts.chart('monthlyTrendsChart', {
        chart: {
            type: 'line',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($monthlyData['months']) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: [{
            title: {
                text: 'Count',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            gridLineColor: '#f1f3f4'
        }, {
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                },
                formatter: function() {
                    return '$' + this.value.toLocaleString();
                }
            },
            opposite: true,
            gridLineColor: '#f1f3f4'
        }],
        legend: {
            itemStyle: {
                color: '#6c757d',
                fontSize: '11px'
            },
            itemMarginTop: 5,
            itemMarginBottom: 5
        },
        tooltip: {
            shared: true,
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            line: {
                marker: {
                    enabled: true,
                    radius: 3
                },
                lineWidth: 2
            }
        },
        series: [{
            name: 'Subscriptions',
            data: {!! json_encode($monthlyData['subscriptions']) !!},
            color: '#3699ff',
            yAxis: 0
        }, {
            name: 'New Users',
            data: {!! json_encode($monthlyData['users']) !!},
            color: '#50cd89',
            yAxis: 0
        }, {
            name: 'Revenue',
            data: {!! json_encode($monthlyData['revenue']) !!},
            color: '#f1416c',
            yAxis: 1
        }]
    });

    // User Distribution Chart
    Highcharts.chart('userDistributionChart', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b><br/>{point.percentage:.1f}%',
                    style: {
                        fontSize: '11px'
                    }
                },
                showInLegend: true
            }
        },
        legend: {
            itemStyle: {
                fontSize: '11px'
            }
        },
        series: [{
            name: 'Users',
            colorByPoint: true,
            data: [{
                name: 'Subscribers',
                y: {{ $usersVsSubscribers['subscribers'] }},
                color: '#50cd89'
            }, {
                name: 'Non-Subscribers',
                y: {{ $usersVsSubscribers['non_subscribers'] }},
                color: '#f1416c'
            }]
        }]
    });

    // Membership Analytics Chart
    Highcharts.chart('membershipAnalyticsChart', {
        chart: {
            type: 'column',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($memberships->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Subscribers',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            column: {
                colorByPoint: true,
                colors: ['#3699ff', '#50cd89', '#f1416c', '#ffc700', '#7239ea'],
                borderRadius: 2
            }
        },
        series: [{
            name: 'Subscribers',
            data: {!! json_encode($memberships->pluck('subscriber_count')) !!}
        }]
    });

    // Class Analytics Chart
    Highcharts.chart('classAnalyticsChart', {
        chart: {
            type: 'bar',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($classSubscriptions->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Subscribers',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            bar: {
                color: '#f1416c',
                borderRadius: 2
            }
        },
        series: [{
            name: 'Subscribers',
            data: {!! json_encode($classSubscriptions->pluck('subscriber_count')) !!}
        }]
    });

    // Revenue Breakdown Chart
    Highcharts.chart('revenueBreakdownChart', {
        chart: {
            type: 'area',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($monthlyData['months']) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                },
                formatter: function() {
                    return '$' + this.value.toLocaleString();
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            },
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' +
                       '<span style="color:' + this.color + '">Revenue: $' + this.y.toLocaleString() + '</span>';
            }
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.color('#50cd89').setOpacity(0.2).get('rgba')],
                        [1, Highcharts.color('#50cd89').setOpacity(0.05).get('rgba')]
                    ]
                },
                marker: {
                    radius: 3
                },
                lineWidth: 2,
                states: {
                    hover: {
                        lineWidth: 2
                    }
                },
                threshold: null
            }
        },
        series: [{
            name: 'Revenue',
            data: {!! json_encode($monthlyData['revenue']) !!},
            color: '#50cd89'
        }]
    });

    // Active vs Inactive Members Chart
    Highcharts.chart('activeInactiveChart', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b><br/>{point.percentage:.1f}%',
                    style: {
                        fontSize: '11px'
                    }
                },
                showInLegend: true
            }
        },
        legend: {
            itemStyle: {
                fontSize: '11px'
            }
        },
        series: [{
            name: 'Members',
            colorByPoint: true,
            data: [{
                name: 'Active',
                y: {{ $membershipAnalytics['active_vs_inactive']['active'] }},
                color: '#50cd89'
            }, {
                name: 'Inactive',
                y: {{ $membershipAnalytics['active_vs_inactive']['inactive'] }},
                color: '#f1416c'
            }]
        }]
    });

    // New Signups per Month Chart
    Highcharts.chart('signupsPerMonthChart', {
        chart: {
            type: 'column',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($membershipAnalytics['signups_per_month']['months']) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'New Signups',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            column: {
                color: '#3699ff',
                borderRadius: 2
            }
        },
        series: [{
            name: 'Signups',
            data: {!! json_encode($membershipAnalytics['signups_per_month']['data']) !!}
        }]
    });

    // Membership Plan Distribution Chart
    Highcharts.chart('membershipPlanChart', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b><br/>{point.percentage:.1f}%',
                    style: {
                        fontSize: '11px'
                    }
                },
                showInLegend: true
            }
        },
        legend: {
            itemStyle: {
                fontSize: '11px'
            }
        },
        series: [{
            name: 'Members',
            colorByPoint: true,
            data: {!! json_encode($membershipAnalytics['membership_distribution']->map(function($item) {
                return ['name' => $item['name'], 'y' => $item['count']];
            })) !!}
        }]
    });

    // Daily Check-ins Chart
    Highcharts.chart('dailyCheckinsChart', {
        chart: {
            type: 'line',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($attendanceAnalytics['daily_checkins']['dates']) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Check-ins',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            line: {
                color: '#50cd89',
                marker: {
                    enabled: true,
                    radius: 3
                },
                lineWidth: 2
            }
        },
        series: [{
            name: 'Check-ins',
            data: {!! json_encode($attendanceAnalytics['daily_checkins']['data']) !!}
        }]
    });

    // Peak Hours Chart
    Highcharts.chart('peakHoursChart', {
        chart: {
            type: 'column',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: ['12 AM', '1 AM', '2 AM', '3 AM', '4 AM', '5 AM', '6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM', '7 PM', '8 PM', '9 PM', '10 PM', '11 PM'],
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Check-ins',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            column: {
                color: '#f1416c',
                borderRadius: 2
            }
        },
        series: [{
            name: 'Check-ins',
            data: {!! json_encode($attendanceAnalytics['peak_hours']) !!}
        }]
    });

    // Branch Check-ins Chart
    Highcharts.chart('branchCheckinsChart', {
        chart: {
            type: 'bar',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($attendanceAnalytics['branch_checkins']->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Check-ins',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            bar: {
                color: '#3699ff',
                borderRadius: 2
            }
        },
        series: [{
            name: 'Check-ins',
            data: {!! json_encode($attendanceAnalytics['branch_checkins']->pluck('checkins')) !!}
        }]
    });


    // Churn Rate Chart
    Highcharts.chart('churnRateChart', {
        chart: {
            type: 'line',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($retentionAnalytics['churn_rate']['months']) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Churn Rate (%)',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            line: {
                color: '#f1416c',
                marker: {
                    enabled: true,
                    radius: 3
                },
                lineWidth: 2
            }
        },
        series: [{
            name: 'Churn Rate',
            data: {!! json_encode($retentionAnalytics['churn_rate']['data']) !!}
        }]
    });

    @if($financialAnalytics)
    // Monthly Revenue Chart
    Highcharts.chart('monthlyRevenueChart', {
        chart: {
            type: 'area',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($financialAnalytics['monthly_revenue']['months']) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                },
                formatter: function() {
                    return '$' + this.value.toLocaleString();
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            },
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' +
                       '<span style="color:' + this.color + '">Revenue: $' + this.y.toLocaleString() + '</span>';
            }
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.color('#50cd89').setOpacity(0.2).get('rgba')],
                        [1, Highcharts.color('#50cd89').setOpacity(0.05).get('rgba')]
                    ]
                },
                marker: {
                    radius: 3
                },
                lineWidth: 2,
                states: {
                    hover: {
                        lineWidth: 2
                    }
                },
                threshold: null
            }
        },
        series: [{
            name: 'Revenue',
            data: {!! json_encode($financialAnalytics['monthly_revenue']['data']) !!},
            color: '#50cd89'
        }]
    });


    // Revenue by Branch Chart
    Highcharts.chart('revenueByBranchChart', {
        chart: {
            type: 'bar',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10]
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($financialAnalytics['revenue_by_branch']->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            lineColor: '#e4e6ea'
        },
        yAxis: {
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                }
            },
            labels: {
                style: {
                    color: '#6c757d',
                    fontSize: '11px'
                },
                formatter: function() {
                    return '$' + this.value.toLocaleString();
                }
            },
            gridLineColor: '#f1f3f4'
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 6,
            shadow: false,
            style: {
                fontSize: '12px'
            },
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' +
                       '<span style="color:' + this.color + '">Revenue: $' + this.y.toLocaleString() + '</span>';
            }
        },
        plotOptions: {
            bar: {
                color: '#3699ff',
                borderRadius: 2
            }
        },
        series: [{
            name: 'Revenue',
            data: {!! json_encode($financialAnalytics['revenue_by_branch']->pluck('revenue')) !!}
        }]
    });
    @endif
});
</script>
@endsection

