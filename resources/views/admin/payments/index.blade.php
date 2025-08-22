@extends('layout.admin.master')

@section('title' , 'Payments Dashboard')

@section('main-breadcrumb', 'Payments')
@section('main-breadcrumb-link', route('payments.index'))

@section('sub-breadcrumb', 'Analytics Dashboard')

@section('content')

<div class="container-fluid py-4">
    <!-- Summary Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-success rounded-circle p-3 me-3">
                            <i class="fas fa-arrow-up text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Total Income</h6>
                            <h4 class="text-dark mb-0 fw-bold">{{ number_format($payments['total_paid']) }} EGP</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success-subtle text-success">
                            {{ count($payments['newest_transactions']) }} transactions
                        </span>
                        <small class="text-muted">This month</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-warning rounded-circle p-3 me-3">
                            <i class="fas fa-clock text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Pending Payments</h6>
                            <h4 class="text-dark mb-0 fw-bold">{{ number_format($payments['total_pending']) }} EGP</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning-subtle text-warning">
                            {{ count($payments['newest_transactions']->where('status', 'pending')) }} pending
                        </span>
                        <small class="text-muted">Awaiting</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-danger rounded-circle p-3 me-3">
                            <i class="fas fa-times text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Failed Payments</h6>
                            <h4 class="text-dark mb-0 fw-bold">{{ number_format($payments['total_failed']) }} EGP</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-danger-subtle text-danger">
                            {{ count($payments['failed_transactions']) }} failed
                        </span>
                        <small class="text-muted">Need attention</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-info rounded-circle p-3 me-3">
                            <i class="fas fa-arrow-down text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Total Outcome</h6>
                            <h4 class="text-dark mb-0 fw-bold">{{ number_format($payments['total_outcome']) }} EGP</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-info-subtle text-info">
                            0 expenses
                        </span>
                        <small class="text-muted">Recorded</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Monthly Revenue Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">
                                Monthly Revenue Trend
                            </h5>
                            <p class="text-muted mb-0">{{ date('Y') }} Revenue Growth</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success-subtle text-success">
                                Growing
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="monthlyRevenueChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Revenue Sources Pie Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">
                            Revenue Sources
                        </h5>
                        <p class="text-muted mb-0">Breakdown by Service Type</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="revenueSourcesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods and Recent Transactions -->
    <div class="row mb-4">
        <!-- Payment Methods -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">
                            Payment Methods
                        </h5>
                        <p class="text-muted mb-0">Distribution by Method</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="paymentMethodsChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">
                                Recent Transactions
                            </h5>
                            <p class="text-muted mb-0">Latest Payment Activities</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4 fw-semibold">Customer</th>
                                    <th class="border-0 fw-semibold">Service</th>
                                    <th class="border-0 text-center fw-semibold">Amount</th>
                                    <th class="border-0 text-center fw-semibold">Status</th>
                                    <th class="border-0 text-center fw-semibold">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments['newest_transactions'] as $transaction)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <img src="{{ $transaction->user->user_image }}" class="rounded-circle" alt="user" width="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $transaction->user->name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $transaction->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-primary-subtle text-primary mb-1">
                                                {{ class_basename($transaction->paymentable_type) ?? 'N/A' }}
                                            </span>
                                            <div class="fw-semibold">{{ $transaction->paymentable->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-success">{{ number_format($transaction->amount) }} EGP</span>
                                    </td>
                                    <td class="text-center">
                                        @if($transaction->status == 'completed')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($transaction->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($transaction->status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-semibold">{{ $transaction->created_at->format('M d') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('Y') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p class="mb-0">No transactions found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Failed Transactions -->
    @if(count($payments['failed_transactions']) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-danger rounded-circle p-2 me-3">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold text-danger">Failed Transactions</h5>
                            <p class="text-muted mb-0">Requires immediate attention</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-danger">
                                <tr>
                                    <th class="border-0 ps-4 fw-semibold">Customer</th>
                                    <th class="border-0 fw-semibold">Service</th>
                                    <th class="border-0 text-center fw-semibold">Amount</th>
                                    <th class="border-0 text-center fw-semibold">Failed Date</th>
                                    <th class="border-0 text-center fw-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments['failed_transactions'] as $transaction)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <img src="{{ asset('assets/admin/img/avatar.jpg') }}" class="rounded-circle" alt="user" width="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $transaction->user->name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $transaction->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-primary-subtle text-primary mb-1">
                                                {{ class_basename($transaction->paymentable_type) ?? 'N/A' }}
                                            </span>
                                            <div class="fw-semibold">{{ $transaction->paymentable->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-danger">{{ number_format($transaction->amount) }} EGP</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-semibold">{{ $transaction->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-danger btn-sm">
                                            Retry
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        labels: {
            style: {
                color: '#6c757d'
            }
        }
    },
    yAxis: {
        title: {
            text: null
        },
        labels: {
            formatter: function() {
                return this.value.toLocaleString() + ' EGP';
            },
            style: {
                color: '#6c757d'
            }
        }
    },
    legend: {
        enabled: false
    },
    plotOptions: {
        area: {
            fillOpacity: 0.1,
            marker: {
                radius: 3
            },
            lineWidth: 2
        }
    },
    series: [{
        name: 'Revenue',
        data: [
            {{ $payments['monthly_revenue']->get(1, 0) }},
            {{ $payments['monthly_revenue']->get(2, 0) }},
            {{ $payments['monthly_revenue']->get(3, 0) }},
            {{ $payments['monthly_revenue']->get(4, 0) }},
            {{ $payments['monthly_revenue']->get(5, 0) }},
            {{ $payments['monthly_revenue']->get(6, 0) }},
            {{ $payments['monthly_revenue']->get(7, 0) }},
            {{ $payments['monthly_revenue']->get(8, 0) }},
            {{ $payments['monthly_revenue']->get(9, 0) }},
            {{ $payments['monthly_revenue']->get(10, 0) }},
            {{ $payments['monthly_revenue']->get(11, 0) }},
            {{ $payments['monthly_revenue']->get(12, 0) }}
        ],
        color: '#198754'
    }],
    tooltip: {
        formatter: function() {
            return '<b>' + this.x + '</b><br/>' +
                   '<span style="color:' + this.color + '">●</span> Revenue: <b>' + 
                   this.y.toLocaleString() + ' EGP</b>';
        }
    },
    credits: {
        enabled: false
    }
});

// Revenue Sources Pie Chart
Highcharts.chart('revenueSourcesChart', {
    chart: {
        type: 'pie',
        backgroundColor: 'transparent'
    },
    title: {
        text: null
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                style: {
                    color: '#6c757d'
                }
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Revenue',
        colorByPoint: true,
        data: [
            @foreach($payments['revenue_by_type'] as $type => $amount)
            {
                name: '{{ $type }}',
                y: {{ $amount }},
                color: Highcharts.getOptions().colors[{{ $loop->index }}]
            },
            @endforeach
        ]
    }],
    tooltip: {
        formatter: function() {
            return '<b>' + this.point.name + '</b><br/>' +
                   'Revenue: <b>' + this.y.toLocaleString() + ' EGP</b> (' + this.percentage.toFixed(1) + '%)';
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        itemStyle: {
            color: '#6c757d'
        }
    },
    credits: {
        enabled: false
    }
});

// Payment Methods Chart
Highcharts.chart('paymentMethodsChart', {
    chart: {
        type: 'pie',
        backgroundColor: 'transparent'
    },
    title: {
        text: null
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br/>{point.percentage:.1f}%',
                style: {
                    color: '#6c757d'
                }
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Payment Methods',
        colorByPoint: true,
        data: [
            @foreach($payments['payment_methods'] as $method)
            {
                name: '{{ $method->payment_method }}',
                y: {{ $method->total_amount }},
                color: '{{ $method->payment_method == "Card" ? "#198754" : "#0d6efd" }}'
            },
            @endforeach
        ]
    }],
    tooltip: {
        formatter: function() {
            return '<b>' + this.point.name + '</b><br/>' +
                   'Amount: <b>' + this.y.toLocaleString() + ' EGP</b> (' + this.percentage.toFixed(1) + '%)';
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        itemStyle: {
            color: '#6c757d'
        }
    },
    credits: {
        enabled: false
    }
});
</script>

<style>
.icon-shape {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar {
    width: 40px;
    height: 40px;
    overflow: hidden;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1);
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1);
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1);
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1);
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.02);
}

.card {
    border-radius: 8px;
}
</style>
@endsection

