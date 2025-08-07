@extends('layout.admin.master')

@section('title','Dashboard')

@section('page-title', 'Dashboard')

@section('main-breadcrumb', 'Dashboard')

@section('sub-breadcrumb','Dashboard')

@section('content')

<!-- Statistics Cards -->
<div class="row g-5 g-xl-10 ">
    <!-- Total Users -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($totalUsers) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Users</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-success fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-success me-1"></i>
                            +{{ number_format($totalUsers) }}
                        </span>
                        <span class="text-gray-500 fs-7">Total registered users</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                    <i class="ki-duotone ki-profile-user fs-2x text-success"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Total Trainers -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($totalTrainers) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Trainers</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-primary fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-primary me-1"></i>
                            +{{ number_format($totalTrainers) }}
                        </span>
                        <span class="text-gray-500 fs-7">Active trainers</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                    <i class="ki-duotone ki-profile-circle fs-2x text-primary"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Total Admins -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($totalAdmins) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Admins</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-warning fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-warning me-1"></i>
                            +{{ number_format($totalAdmins) }}
                        </span>
                        <span class="text-gray-500 fs-7">System administrators</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                    <i class="ki-duotone ki-shield-tick fs-2x text-warning"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Total Branches -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($totalBranches) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Branches</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-info fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-info me-1"></i>
                            +{{ number_format($totalBranches) }}
                        </span>
                        <span class="text-gray-500 fs-7">Gym locations</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-info d-block my-2">
                    <i class="ki-duotone ki-geolocation fs-2x text-info"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Second Row of Statistics -->
<div class="row g-5 g-xl-10">
    <!-- Total Classes -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($totalClasses) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Classes</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-danger fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-danger me-1"></i>
                            +{{ number_format($totalClasses) }}
                        </span>
                        <span class="text-gray-500 fs-7">Available classes</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                    <i class="ki-duotone ki-calendar fs-2x text-danger"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Total Services -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($totalServices) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Services</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-dark fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-dark me-1"></i>
                            +{{ number_format($totalServices) }}
                        </span>
                        <span class="text-gray-500 fs-7">Available services</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-dark d-block my-2">
                    <i class="ki-duotone ki-gear fs-2x text-dark"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Active Subscriptions -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($activeSubscriptions) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Active Subscriptions</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-success fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-success me-1"></i>
                            +{{ number_format($activeSubscriptions) }}
                        </span>
                        <span class="text-gray-500 fs-7">Current subscriptions</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                    <i class="ki-duotone ki-check-circle fs-2x text-success"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Expired Subscriptions -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($expiredSubscriptions) }}</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Expired Subscriptions</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-danger fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-down fs-7 text-danger me-1"></i>
                            -{{ number_format($expiredSubscriptions) }}
                        </span>
                        <span class="text-gray-500 fs-7">Expired subscriptions</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                    <i class="ki-duotone ki-cross-circle fs-2x text-danger"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-5 g-xl-10">
    <!-- Users vs Subscribers Chart -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Users vs Subscribers</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total users compared to active subscribers</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-success fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-success me-1"></i>
                            {{ number_format($usersVsSubscribers['subscribers']) }} Subscribers
                        </span>
                        <span class="text-gray-500 fs-7">out of {{ number_format($usersVsSubscribers['users']) }} total users</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="usersVsSubscribersChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Subscriptions Per Month Chart -->
    <div class="col-xl-6">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Subscriptions Per Month</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">New subscriptions over the last 12 months</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="subscriptionsPerMonthChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Membership and Class Charts Row -->
<div class="row g-5 g-xl-10">
    <!-- Membership Subscriptions Chart -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Membership Subscriptions</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Number of users subscribed to each membership</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="membershipSubscriptionsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Class Subscriptions Chart -->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Class Subscriptions</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Number of users subscribed to each class</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="classSubscriptionsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Users vs Subscribers Chart
    const usersVsSubscribersCtx = document.getElementById('usersVsSubscribersChart').getContext('2d');
    new Chart(usersVsSubscribersCtx, {
        type: 'doughnut',
        data: {
            labels: ['Subscribers', 'Non-Subscribers'],
            datasets: [{
                data: [{{ $usersVsSubscribers['subscribers'] }}, {{ $usersVsSubscribers['non_subscribers'] }}],
                backgroundColor: ['#50cd89', '#f1416c'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Subscriptions Per Month Chart
    const subscriptionsPerMonthCtx = document.getElementById('subscriptionsPerMonthChart').getContext('2d');
    new Chart(subscriptionsPerMonthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($subscriptionsPerMonth->pluck('month')) !!},
            datasets: [{
                label: 'Subscriptions',
                data: {!! json_encode($subscriptionsPerMonth->pluck('count')) !!},
                borderColor: '#3699ff',
                backgroundColor: 'rgba(54, 153, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Membership Subscriptions Chart
    const membershipSubscriptionsCtx = document.getElementById('membershipSubscriptionsChart').getContext('2d');
    new Chart(membershipSubscriptionsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($memberships->pluck('name')) !!},
            datasets: [{
                label: 'Subscribers',
                data: {!! json_encode($memberships->pluck('subscriber_count')) !!},
                backgroundColor: '#50cd89',
                borderColor: '#50cd89',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Class Subscriptions Chart
    const classSubscriptionsCtx = document.getElementById('classSubscriptionsChart').getContext('2d');
    new Chart(classSubscriptionsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($classSubscriptions->pluck('name')) !!},
            datasets: [{
                label: 'Subscribers',
                data: {!! json_encode($classSubscriptions->pluck('subscriber_count')) !!},
                backgroundColor: '#f1416c',
                borderColor: '#f1416c',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection
