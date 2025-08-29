@extends('layout.admin.master')

@section('title','Dashboard')

@section('page-title', 'Dashboard')

@section('main-breadcrumb', 'Dashboard')

@section('sub-breadcrumb','Dashboard')

@section('content')

<!-- Modern Dashboard Grid Layout -->
<div class="row g-4 g-xl-8 mb-8">
    <!-- Row 1: Key Metrics -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-custom card-stretch bg-gradient-primary-to-secondary">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-white-75 fs-7 fw-semibold mb-1">Total Users</span>
                        <span class="fs-2hx fw-bold text-white">{{ number_format($totalUsers) }}</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-light-success fs-8 fw-bold me-2">
                                <i class="ki-duotone ki-arrow-up fs-8 text-success me-1"></i>
                                +{{ number_format($userGrowthData['growth'], 1) }}%
                            </span>
                            <span class="text-white-75 fs-8">vs last month</span>
                        </div>
                    </div>
                    <div class="symbol symbol-50px symbol-circle bg-white bg-opacity-20">
                        <i class="ki-duotone ki-profile-user fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-custom card-stretch bg-gradient-info-to-primary">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-white-75 fs-7 fw-semibold mb-1">Active Members</span>
                        <span class="fs-2hx fw-bold text-white">{{ number_format($membershipAnalytics['active_vs_inactive']['active']) }}</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-light-info fs-8 fw-bold me-2">
                                <i class="ki-duotone ki-check-circle fs-8 text-info me-1"></i>
                                Active
                            </span>
                            <span class="text-white-75 fs-8">{{ number_format($membershipAnalytics['active_vs_inactive']['inactive']) }} inactive</span>
                        </div>
                    </div>
                    <div class="symbol symbol-50px symbol-circle bg-white bg-opacity-20">
                        <i class="ki-duotone ki-profile-circle fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-custom card-stretch bg-gradient-success-to-info">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-white-75 fs-7 fw-semibold mb-1">Monthly Revenue</span>
                        <span class="fs-2hx fw-bold text-white">${{ number_format($revenueData['current']) }}</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-light-success fs-8 fw-bold me-2">
                                <i class="ki-duotone ki-arrow-up fs-8 text-success me-1"></i>
                                +{{ number_format($revenueData['growth'], 1) }}%
                            </span>
                            <span class="text-white-75 fs-8">vs last month</span>
                        </div>
                    </div>
                    <div class="symbol symbol-50px symbol-circle bg-white bg-opacity-20">
                        <i class="ki-duotone ki-dollar fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-custom card-stretch bg-gradient-warning-to-danger">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-white-75 fs-7 fw-semibold mb-1">Active Subscriptions</span>
                        <span class="fs-2hx fw-bold text-white">{{ number_format($subscriptionStats['active']) }}</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-light-warning fs-8 fw-bold me-2">
                                <i class="ki-duotone ki-arrow-up fs-8 text-warning me-1"></i>
                                +{{ number_format($subscriptionStats['growth_rate'], 1) }}%
                            </span>
                            <span class="text-white-75 fs-8">vs last month</span>
                        </div>
                    </div>
                    <div class="symbol symbol-50px symbol-circle bg-white bg-opacity-20">
                        <i class="ki-duotone ki-check-circle fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Secondary Metrics -->
<div class="row g-4 g-xl-8 mb-8">
    <div class="col-xl-3 col-md-6">
        <div class="card card-custom card-stretch bg-gradient-danger-to-warning">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-white-75 fs-7 fw-semibold mb-1">Total Trainers</span>
                        <span class="fs-2hx fw-bold text-white">{{ number_format($totalTrainers) }}</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-light-danger fs-8 fw-bold me-2">
                                <i class="ki-duotone ki-profile-circle fs-8 text-danger me-1"></i>
                                Professional
                            </span>
                            <span class="text-white-75 fs-8">Active trainers</span>
                        </div>
                    </div>
                    <div class="symbol symbol-50px symbol-circle bg-white bg-opacity-20">
                        <i class="ki-duotone ki-profile-circle fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-custom card-stretch bg-gradient-dark-to-gray">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-white-75 fs-7 fw-semibold mb-1">Churn Rate</span>
                        <span class="fs-2hx fw-bold text-white">{{ number_format($retentionAnalytics['churn_rate']['data'][2] ?? 0, 1) }}%</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-light-dark fs-8 fw-bold me-2">
                                <i class="ki-duotone ki-arrow-down fs-8 text-dark me-1"></i>
                                This Month
                            </span>
                            <span class="text-white-75 fs-8">Member retention</span>
                        </div>
                    </div>
                    <div class="symbol symbol-50px symbol-circle bg-white bg-opacity-20">
                        <i class="ki-duotone ki-chart-line fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-custom card-stretch bg-gradient-primary-to-info">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-white-75 fs-7 fw-semibold mb-1">Avg Duration</span>
                        <span class="fs-2hx fw-bold text-white">{{ number_format($retentionAnalytics['avg_membership_duration']) }}</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-light-primary fs-8 fw-bold me-2">
                                <i class="ki-duotone ki-calendar fs-8 text-primary me-1"></i>
                                Days
                            </span>
                            <span class="text-white-75 fs-8">Membership duration</span>
                        </div>
                    </div>
                    <div class="symbol symbol-50px symbol-circle bg-white bg-opacity-20">
                        <i class="ki-duotone ki-calendar fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-custom card-stretch bg-gradient-warning-to-danger">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-white-75 fs-7 fw-semibold mb-1">Outstanding</span>
                        <span class="fs-2hx fw-bold text-white">${{ number_format($financialAnalytics['outstanding_payments'] ?? 0) }}</span>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-light-warning fs-8 fw-bold me-2">
                                <i class="ki-duotone ki-warning fs-8 text-warning me-1"></i>
                                Pending
                            </span>
                            <span class="text-white-75 fs-8">Payments due</span>
                        </div>
                    </div>
                    <div class="symbol symbol-50px symbol-circle bg-white bg-opacity-20">
                        <i class="ki-duotone ki-dollar fs-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics Row -->
<div class="row g-5 g-xl-10 mb-5">
    <!-- Conversion Rate -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $performanceMetrics['conversion_rate'] }}%</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Conversion Rate</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4">
                <div class="progress h-7px bg-light-primary">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $performanceMetrics['conversion_rate'] }}%"></div>
                </div>
                <span class="text-gray-500 fs-7">Users to memberships</span>
            </div>
        </div>
    </div>

    <!-- Average Class Attendance -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $performanceMetrics['avg_class_attendance'] }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Avg Class Attendance</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4">
                <div class="d-flex align-items-center">
                    <span class="badge badge-light-success fs-7 fw-bold me-2">
                        <i class="ki-duotone ki-arrow-up fs-7 text-success me-1"></i>
                        Good
                    </span>
                    <span class="text-gray-500 fs-7">Per class average</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Branches -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($totalBranches) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Branches</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-info fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-geolocation fs-7 text-info me-1"></i>
                            Active
                        </span>
                        <span class="text-gray-500 fs-7">Gym locations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Classes -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($totalClasses) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Classes</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-danger fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-calendar fs-7 text-danger me-1"></i>
                            Available
                        </span>
                        <span class="text-gray-500 fs-7">Active classes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Charts Row -->
<div class="row g-4 g-xl-8 mb-8">
    <!-- Monthly Trends Chart -->
    <div class="col-xl-8">
        <div class="card card-custom h-xl-100">
            <div class="card-header pt-6 pb-4">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Monthly Trends</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Revenue, Users & Subscriptions (Last 12 Months)</span>
                </div>
            </div>
            <div class="card-body chart-container">
                <div id="monthlyTrendsChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- Users vs Subscribers Chart -->
    <div class="col-xl-4">
        <div class="card card-custom h-xl-100">
            <div class="card-header pt-6 pb-4">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">User Distribution</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Subscribers vs Non-Subscribers</span>
                </div>
            </div>
            <div class="card-body chart-container">
                <div id="userDistributionChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Membership and Class Analytics -->
<div class="row g-5 g-xl-10">
    <!-- Membership Analytics -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Membership Analytics</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Subscribers per membership type</span>
                </div>
            </div>
            <div class="card-body">
                <div id="membershipAnalyticsChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Class Analytics -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Class Analytics</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Subscribers per class</span>
                </div>
            </div>
            <div class="card-body">
                <div id="classAnalyticsChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue and Performance Dashboard -->
<div class="row g-5 g-xl-10">
    <!-- Revenue Breakdown -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Revenue Breakdown</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Monthly revenue trends</span>
                </div>
            </div>
            <div class="card-body">
                <div id="revenueBreakdownChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Performance Overview</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Key performance indicators</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="fs-6 text-gray-500 fw-semibold">Conversion Rate</span>
                            <span class="fs-2 fw-bold text-dark">{{ $performanceMetrics['conversion_rate'] }}%</span>
                            <div class="progress h-4px bg-light-primary mt-2">
                                <div class="progress-bar bg-primary" style="width: {{ $performanceMetrics['conversion_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="fs-6 text-gray-500 fw-semibold">Avg Attendance</span>
                            <span class="fs-2 fw-bold text-dark">{{ $performanceMetrics['avg_class_attendance'] }}</span>
                            <span class="fs-7 text-gray-500">per class</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="fs-6 text-gray-500 fw-semibold">Active Subscriptions</span>
                            <span class="fs-2 fw-bold text-dark">{{ number_format($subscriptionStats['active']) }}</span>
                            <span class="fs-7 text-success">+{{ number_format($subscriptionStats['growth_rate'], 1) }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="fs-6 text-gray-500 fw-semibold">Monthly Revenue</span>
                            <span class="fs-2 fw-bold text-dark">${{ number_format($revenueData['current']) }}</span>
                            <span class="fs-7 text-success">+{{ number_format($revenueData['growth'], 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Membership & Users Analytics -->
<div class="row g-4 g-xl-8 mb-8">
    <!-- Active vs Inactive Members Chart -->
    <div class="col-xl-6">
        <div class="card card-custom h-xl-100">
            <div class="card-header pt-6 pb-4">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Active vs Inactive Members</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Member activity status</span>
                </div>
            </div>
            <div class="card-body chart-container">
                <div id="activeInactiveChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- New Signups per Month -->
    <div class="col-xl-6">
        <div class="card card-custom h-xl-100">
            <div class="card-header pt-6 pb-4">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">New Signups per Month</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Last 6 months trend</span>
                </div>
            </div>
            <div class="card-body chart-container">
                <div id="signupsPerMonthChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Membership Plan Distribution & Expiring Memberships -->
<div class="row g-4 g-xl-8 mb-8">
    <!-- Membership Plan Distribution -->
    <div class="col-xl-6">
        <div class="card card-custom h-xl-100">
            <div class="card-header pt-6 pb-4">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Membership Plan Distribution</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Subscribers per plan type</span>
                </div>
            </div>
            <div class="card-body chart-container">
                <div id="membershipPlanChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Expiring Memberships -->
    <div class="col-xl-6">
        <div class="card card-custom h-xl-100">
            <div class="card-header pt-6 pb-4">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Expiring Memberships</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Next 30 days</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive modern-table">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="w-25px">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-13-check" />
                                    </div>
                                </th>
                                <th class="min-w-150px">Member</th>
                                <th class="min-w-140px">Plan</th>
                                <th class="min-w-120px">Expires</th>
                                <th class="min-w-100px text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($membershipAnalytics['expiring_memberships'] as $membership)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input widget-13-check" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td class="text-dark fw-bold text-hover-primary fs-6">{{ $membership->user->name ?? 'N/A' }}</td>
                                <td class="text-dark fw-bold text-hover-primary fs-6">{{ $membership->bookable->name ?? 'N/A' }}</td>
                                <td class="text-dark fw-bold text-hover-primary fs-6">{{ $membership->created_at->addMonth()->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <i class="ki-duotone ki-message-text-2 fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No expiring memberships</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance & Check-ins Analytics -->
<div class="row g-5 g-xl-10">
    <!-- Daily Check-ins -->
    <div class="col-xl-8 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Daily Check-ins</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Last 30 days attendance</span>
                </div>
            </div>
            <div class="card-body">
                <div id="dailyCheckinsChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- Peak Hours -->
    <div class="col-xl-4 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Peak Hours</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Busiest times of day</span>
                </div>
            </div>
            <div class="card-body">
                <div id="peakHoursChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Branch Check-ins & Machine Usage -->
<div class="row g-5 g-xl-10">
    <!-- Branch-wise Check-ins -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Branch-wise Check-ins</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Last 30 days</span>
                </div>
            </div>
            <div class="card-body">
                <div id="branchCheckinsChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Most Used Machines -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Most Used Machines</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Top 10 facilities</span>
                </div>
            </div>
            <div class="card-body">
                @if($attendanceAnalytics['machine_usage']->count() > 0)
                    <div id="machineUsageChart" style="height: 350px;"></div>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                        <div class="fs-6 text-gray-500 mb-2">Machine usage tracking not available</div>
                        <div class="fs-7 text-gray-400">This feature requires machine check-in integration</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Trainers & Classes Analytics -->
<div class="row g-5 g-xl-10">
    <!-- Trainer Sessions Count -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Trainer Sessions Count</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Last month sessions</span>
                </div>
            </div>
            <div class="card-body">
                <div id="trainerSessionsChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Class Attendance Over Time -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Class Attendance Over Time</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Last month attendance</span>
                </div>
            </div>
            <div class="card-body">
                <div id="classAttendanceChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Class Occupancy & Top Trainers -->
<div class="row g-5 g-xl-10">
    <!-- Class Occupancy Rate -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Class Occupancy Rate</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Seat utilization</span>
                </div>
            </div>
            <div class="card-body">
                <div id="classOccupancyChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Top-rated Trainers -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Top-rated Trainers</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Based on performance</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="w-25px">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-13-check" />
                                    </div>
                                </th>
                                <th class="min-w-150px">Trainer</th>
                                <th class="min-w-140px">Rating</th>
                                <th class="min-w-120px">Sessions</th>
                                <th class="min-w-100px text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trainerAnalytics['top_rated_trainers'] as $trainer)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input widget-13-check" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td class="text-dark fw-bold text-hover-primary fs-6">{{ $trainer['name'] }}</td>
                                <td class="text-dark fw-bold text-hover-primary fs-6">
                                    <span class="badge badge-light-success">{{ $trainer['rating'] }}/5.0</span>
                                </td>
                                <td class="text-dark fw-bold text-hover-primary fs-6">{{ $trainer['sessions'] }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <i class="ki-duotone ki-profile-circle fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No trainer data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Retention & Engagement Analytics -->
<div class="row g-5 g-xl-10">
    <!-- Churn Rate -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Churn Rate</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Last 3 months trend</span>
                </div>
            </div>
            <div class="card-body">
                <div id="churnRateChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Average Membership Duration -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Membership Duration</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Average days before cancellation</span>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                    <div class="fs-1 fw-bold text-dark mb-2">{{ number_format($retentionAnalytics['avg_membership_duration']) }}</div>
                    <div class="fs-6 text-gray-500">Average Days</div>
                    <div class="progress w-100 mt-3" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: {{ min(100, ($retentionAnalytics['avg_membership_duration'] / 365) * 100) }}%"></div>
                    </div>
                    <div class="fs-7 text-gray-500 mt-2">of 365 days</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($financialAnalytics)
<!-- Financial Analytics (Permission-based) -->
<div class="row g-5 g-xl-10">
    <!-- Monthly Revenue -->
    <div class="col-xl-8 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Monthly Revenue</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Last 12 months trend</span>
                </div>
            </div>
            <div class="card-body">
                <div id="monthlyRevenueChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- Revenue by Membership Plan -->
    <div class="col-xl-4 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Revenue by Plan</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">This month breakdown</span>
                </div>
            </div>
            <div class="card-body">
                <div id="revenueByPlanChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue by Branch & Outstanding Payments -->
<div class="row g-5 g-xl-10">
    <!-- Revenue by Branch -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Revenue by Branch</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">This month performance</span>
                </div>
            </div>
            <div class="card-body">
                <div id="revenueByBranchChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Outstanding Payments -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Outstanding Payments</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Pending dues</span>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                    <div class="fs-1 fw-bold text-warning mb-2">${{ number_format($financialAnalytics['outstanding_payments']) }}</div>
                    <div class="fs-6 text-gray-500">Total Outstanding</div>
                    <div class="badge badge-light-warning fs-7 mt-2">
                        <i class="ki-duotone ki-warning fs-7 text-warning me-1"></i>
                        Requires Attention
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('css')
<style>
/* Custom Dashboard Styles */
.bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #3699ff 0%, #8b5cf6 100%);
}

.bg-gradient-info-to-primary {
    background: linear-gradient(135deg, #17a2b8 0%, #3699ff 100%);
}

.bg-gradient-success-to-info {
    background: linear-gradient(135deg, #50cd89 0%, #17a2b8 100%);
}

.bg-gradient-warning-to-danger {
    background: linear-gradient(135deg, #ffc700 0%, #f1416c 100%);
}

.bg-gradient-danger-to-warning {
    background: linear-gradient(135deg, #f1416c 0%, #ffc700 100%);
}

.bg-gradient-dark-to-gray {
    background: linear-gradient(135deg, #181c32 0%, #6c757d 100%);
}

.bg-gradient-primary-to-info {
    background: linear-gradient(135deg, #3699ff 0%, #17a2b8 100%);
}

.card-custom {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card-custom:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.symbol-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-opacity-20 {
    background-color: rgba(255, 255, 255, 0.2) !important;
}

.fs-8 {
    font-size: 0.75rem !important;
}

/* Chart container improvements */
.chart-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

/* Modern table styling */
.modern-table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.modern-table .table {
    margin-bottom: 0;
}

.modern-table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #6c757d;
}

.modern-table td {
    border: none;
    border-bottom: 1px solid #f1f3f4;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .fs-2hx {
        font-size: 1.5rem !important;
    }
    
    .symbol-50px {
        width: 40px !important;
        height: 40px !important;
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
    // Monthly Trends Chart
    Highcharts.chart('monthlyTrendsChart', {
        chart: {
            type: 'spline',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($monthlyData['months']) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: [{
            title: {
                text: 'Count',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        }, {
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                },
                formatter: function() {
                    return '$' + this.value.toLocaleString();
                }
            },
            opposite: true
        }],
        legend: {
            itemStyle: {
                color: '#6c757d'
            }
        },
        tooltip: {
            shared: true,
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                showInLegend: true
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($memberships->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Subscribers',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            column: {
                colorByPoint: true,
                colors: ['#3699ff', '#50cd89', '#f1416c', '#ffc700', '#7239ea']
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($classSubscriptions->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Subscribers',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            bar: {
                color: '#f1416c'
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($monthlyData['months']) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                },
                formatter: function() {
                    return '$' + this.value.toLocaleString();
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true,
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
                        [0, Highcharts.color('#50cd89').setOpacity(0.3).get('rgba')],
                        [1, Highcharts.color('#50cd89').setOpacity(0.1).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                showInLegend: true
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($membershipAnalytics['signups_per_month']['months']) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'New Signups',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            column: {
                color: '#3699ff'
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                showInLegend: true
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($attendanceAnalytics['daily_checkins']['dates']) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Check-ins',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            line: {
                color: '#50cd89',
                marker: {
                    enabled: true
                }
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: ['12 AM', '1 AM', '2 AM', '3 AM', '4 AM', '5 AM', '6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM', '7 PM', '8 PM', '9 PM', '10 PM', '11 PM'],
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Check-ins',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            column: {
                color: '#f1416c'
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($attendanceAnalytics['branch_checkins']->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Check-ins',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            bar: {
                color: '#3699ff'
            }
        },
        series: [{
            name: 'Check-ins',
            data: {!! json_encode($attendanceAnalytics['branch_checkins']->pluck('checkins')) !!}
        }]
    });

    // Machine Usage Chart
    @if($attendanceAnalytics['machine_usage']->count() > 0)
    Highcharts.chart('machineUsageChart', {
        chart: {
            type: 'bar',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($attendanceAnalytics['machine_usage']->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Usage Count',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            bar: {
                color: '#ffc700'
            }
        },
        series: [{
            name: 'Usage',
            data: {!! json_encode($attendanceAnalytics['machine_usage']->pluck('usage_count')) !!}
        }]
    });
    @endif

    // Trainer Sessions Chart
    Highcharts.chart('trainerSessionsChart', {
        chart: {
            type: 'bar',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($trainerAnalytics['trainer_sessions']->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Sessions',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            bar: {
                color: '#7239ea'
            }
        },
        series: [{
            name: 'Sessions',
            data: {!! json_encode($trainerAnalytics['trainer_sessions']->pluck('sessions_count')) !!}
        }]
    });

    // Class Attendance Chart
    Highcharts.chart('classAttendanceChart', {
        chart: {
            type: 'column',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($trainerAnalytics['class_attendance']->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Attendance',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            column: {
                color: '#50cd89'
            }
        },
        series: [{
            name: 'Attendance',
            data: {!! json_encode($trainerAnalytics['class_attendance']->pluck('attendance')) !!}
        }]
    });

    // Class Occupancy Chart
    Highcharts.chart('classOccupancyChart', {
        chart: {
            type: 'column',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($trainerAnalytics['class_occupancy']->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Occupancy Rate (%)',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true,
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' +
                       '<span style="color:' + this.color + '">Occupancy: ' + this.y + '%</span>';
            }
        },
        plotOptions: {
            column: {
                color: '#f1416c'
            }
        },
        series: [{
            name: 'Occupancy',
            data: {!! json_encode($trainerAnalytics['class_occupancy']->pluck('occupancy_rate')) !!}
        }]
    });

    // Churn Rate Chart
    Highcharts.chart('churnRateChart', {
        chart: {
            type: 'line',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($retentionAnalytics['churn_rate']['months']) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Churn Rate (%)',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true
        },
        plotOptions: {
            line: {
                color: '#f1416c',
                marker: {
                    enabled: true
                }
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
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($financialAnalytics['monthly_revenue']['months']) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                },
                formatter: function() {
                    return '$' + this.value.toLocaleString();
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true,
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
                        [0, Highcharts.color('#50cd89').setOpacity(0.3).get('rgba')],
                        [1, Highcharts.color('#50cd89').setOpacity(0.1).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
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

    // Revenue by Plan Chart
    Highcharts.chart('revenueByPlanChart', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>${point.y:,.0f}</b> ({point.percentage:.1f}%)'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b><br/>${point.y:,.0f}'
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Revenue',
            colorByPoint: true,
            data: {!! json_encode($financialAnalytics['revenue_by_plan']->map(function($item) {
                return ['name' => $item['name'], 'y' => $item['revenue']];
            })) !!}
        }]
    });

    // Revenue by Branch Chart
    Highcharts.chart('revenueByBranchChart', {
        chart: {
            type: 'bar',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: {!! json_encode($financialAnalytics['revenue_by_branch']->pluck('name')) !!},
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#6c757d'
                }
            },
            labels: {
                style: {
                    color: '#6c757d'
                },
                formatter: function() {
                    return '$' + this.value.toLocaleString();
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            borderColor: '#e4e6ea',
            borderRadius: 8,
            shadow: true,
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' +
                       '<span style="color:' + this.color + '">Revenue: $' + this.y.toLocaleString() + '</span>';
            }
        },
        plotOptions: {
            bar: {
                color: '#3699ff'
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

