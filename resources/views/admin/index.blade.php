@extends('layout.admin.master')

@section('title','Dashboard')

@section('page-title', 'Dashboard')

@section('main-breadcrumb', 'Dashboard')

@section('sub-breadcrumb','Dashboard')

@section('content')

<!-- Enhanced Statistics Cards -->
<div class="row g-5 g-xl-10">
    <!-- Total Users -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100 bg-gradient-primary">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ number_format($totalUsers) }}</span>
                    </div>
                    <span class="text-white-75 pt-1 fw-semibold fs-6">Total Users</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-success fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-success me-1"></i>
                            +{{ number_format($userGrowthData['growth'], 1) }}%
                        </span>
                        <span class="text-white-75 fs-7">vs last month</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                    <i class="ki-duotone ki-profile-user fs-2x text-white"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Total Trainers -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100 bg-gradient-info">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ number_format($totalTrainers) }}</span>
                    </div>
                    <span class="text-white-75 pt-1 fw-semibold fs-6">Total Trainers</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-primary fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-primary me-1"></i>
                            Active
                        </span>
                        <span class="text-white-75 fs-7">Professional trainers</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                    <i class="ki-duotone ki-profile-circle fs-2x text-white"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100 bg-gradient-success">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-4 fw-semibold text-white-75 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ number_format($revenueData['current']) }}</span>
                    </div>
                    <span class="text-white-75 pt-1 fw-semibold fs-6">Monthly Revenue</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-success fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-success me-1"></i>
                            +{{ number_format($revenueData['growth'], 1) }}%
                        </span>
                        <span class="text-white-75 fs-7">vs last month</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                    <i class="ki-duotone ki-dollar fs-2x text-white"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Active Subscriptions -->
    <div class="col-md-6 col-lg-3 col-xl-3 col-xxl-3 mb-5">
        <div class="card card-flush h-xl-100 bg-gradient-warning">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ number_format($subscriptionStats['active']) }}</span>
                    </div>
                    <span class="text-white-75 pt-1 fw-semibold fs-6">Active Subscriptions</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex fw-semibold align-items-center">
                        <span class="badge badge-light-warning fs-7 fw-bold me-2">
                            <i class="ki-duotone ki-arrow-up fs-7 text-warning me-1"></i>
                            +{{ number_format($subscriptionStats['growth_rate'], 1) }}%
                        </span>
                        <span class="text-white-75 fs-7">vs last month</span>
                    </div>
                </div>
                <span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                    <i class="ki-duotone ki-check-circle fs-2x text-white"></i>
                </span>
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
<div class="row g-5 g-xl-10">
    <!-- Monthly Trends Chart -->
    <div class="col-xl-8 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Monthly Trends</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Revenue, Users & Subscriptions (Last 12 Months)</span>
                </div>
            </div>
            <div class="card-body">
                <div id="monthlyTrendsChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- Users vs Subscribers Chart -->
    <div class="col-xl-4 mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">User Distribution</span>
                    </div>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Subscribers vs Non-Subscribers</span>
                </div>
            </div>
            <div class="card-body">
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
});
</script>
@endsection

