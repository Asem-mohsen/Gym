@extends('layout.admin.master')

@section('title', 'Check-in Statistics - ' . $gym->gym_name)

@section('main-breadcrumb', 'Check-in Settings')
@section('main-breadcrumb-link', route('admin.checkin-settings.index'))

@section('sub-breadcrumb', 'Statistics')

@section('toolbar-actions')
    <a href="{{ route('admin.checkin-settings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Check-in Settings
    </a>
@endsection

@section('content')
<div class="row">
    <!-- Period Filter -->
    <div class="col-12 mb-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Statistics</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="period" class="form-label">Time Period</label>
                        <select class="form-select" id="period" name="period">
                            <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Apply Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-md-3 mb-5">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fs-2x mb-2 text-white"></i>
                <h3 class="text-white">{{ number_format($stats['total_checkins'] ?? 0) }}</h3>
                <p class="mb-0 fw-bold">Total Check-ins</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-5">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-user-friends fs-2x mb-2 text-white"></i>
                <h3 class="text-white">{{ number_format($stats['unique_users'] ?? 0) }}</h3>
                <p class="mb-0 fw-bold">Unique Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-5">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-mobile-alt fs-2x mb-2 text-white"></i>
                <h3 class="text-white">{{ number_format($stats['self_scans'] ?? 0) }}</h3>
                <p class="mb-0 fw-bold">Self Scans</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-5">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-camera fs-2x mb-2 text-white"></i>
                <h3 class="text-white">{{ number_format($stats['gate_scans'] ?? 0) }}</h3>
                <p class="mb-0 fw-bold">Gate Scans</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Check-in Activity</h3>
            </div>
            <div class="card-body">
                <canvas id="checkinChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Check-ins -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Check-ins</h3>
            </div>
            <div class="card-body">
                @if($recentCheckins && $recentCheckins->count() > 0)
                    <div class="timeline">
                        @foreach($recentCheckins as $checkin)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $checkin->checkin_type === 'self_scan' ? 'bg-primary' : 'bg-success' }}"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $checkin->user->name ?? 'Unknown User' }}</strong>
                                        <small class="text-muted">{{ $checkin->created_at ? $checkin->created_at->diffForHumans() : 'Unknown time' }}</small>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-{{ $checkin->checkin_type === 'self_scan' ? 'mobile-alt' : 'camera' }} me-1"></i>
                                        {{ $checkin->checkin_type_label }}
                                        @if($checkin->branch)
                                            <br><i class="fas fa-building me-1"></i>{{ $checkin->branch->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No check-ins found for this period.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Check-in Summary</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Method Distribution</h5>
                        <div class="progress mb-3">
                            @php
                                $total = ($stats['self_scans'] ?? 0) + ($stats['gate_scans'] ?? 0);
                                $selfScanPercent = $total > 0 ? (($stats['self_scans'] ?? 0) / $total) * 100 : 0;
                                $gateScanPercent = $total > 0 ? (($stats['gate_scans'] ?? 0) / $total) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-primary" style="width: {{ $selfScanPercent }}%">
                                Self Scan ({{ number_format($selfScanPercent, 1) }}%)
                            </div>
                            <div class="progress-bar bg-success" style="width: {{ $gateScanPercent }}%">
                                Gate Scan ({{ number_format($gateScanPercent, 1) }}%)
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Activity Summary</h5>
                        <ul class="list-unstyled">
                            <li><strong>Period:</strong> {{ ucfirst($period) }}</li>
                            <li><strong>Total Check-ins:</strong> {{ number_format($stats['total_checkins'] ?? 0) }}</li>
                            <li><strong>Unique Users:</strong> {{ number_format($stats['unique_users'] ?? 0) }}</li>
                            <li><strong>Average per User:</strong> {{ $stats['unique_users'] > 0 ? number_format(($stats['total_checkins'] ?? 0) / $stats['unique_users'], 1) : 0 }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create chart
            const ctx = document.getElementById('checkinChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Self Scan', 'Gate Scan'],
                    datasets: [{
                        label: 'Check-ins',
                        data: [
                            {{ $stats['self_scans'] ?? 0 }},
                            {{ $stats['gate_scans'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(13, 110, 253, 0.8)',
                            'rgba(25, 135, 84, 0.8)'
                        ],
                        borderColor: [
                            'rgba(13, 110, 253, 1)',
                            'rgba(25, 135, 84, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection

