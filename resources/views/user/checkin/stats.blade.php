@extends('layout.user.master')

@section('title', 'Check-in Statistics - ' . $gym->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Check-in Statistics - {{ $gym->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Period Selector -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex">
                                <select name="period" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                                </select>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-filter me-2"></i>
                                    Filter
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('user.checkin.staff-scanner', $gym->slug) }}" class="btn btn-success">
                                <i class="fas fa-camera me-2"></i>
                                Staff Scanner
                            </a>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <h3>{{ number_format($stats['total_checkins']) }}</h3>
                                    <p class="mb-0">Total Check-ins</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-friends fa-2x mb-2"></i>
                                    <h3>{{ number_format($stats['unique_users']) }}</h3>
                                    <p class="mb-0">Unique Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-mobile-alt fa-2x mb-2"></i>
                                    <h3>{{ number_format($stats['self_scans']) }}</h3>
                                    <p class="mb-0">Self Scans</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-camera fa-2x mb-2"></i>
                                    <h3>{{ number_format($stats['gate_scans']) }}</h3>
                                    <p class="mb-0">Gate Scans</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Check-in Types Distribution</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="checkinTypesChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Daily Check-ins Trend</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="dailyTrendChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Check-ins Table -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Recent Check-ins</h6>
                        </div>
                        <div class="card-body">
                            <div id="recent-checkins-table">
                                <!-- Recent check-ins will be loaded here via AJAX -->
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Export Options -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-download me-2"></i>Export Options</h6>
                            <div class="d-grid gap-2 d-md-block">
                                <button class="btn btn-outline-primary" onclick="exportData('csv')">
                                    <i class="fas fa-file-csv me-2"></i>
                                    Export CSV
                                </button>
                                <button class="btn btn-outline-secondary" onclick="exportData('pdf')">
                                    <i class="fas fa-file-pdf me-2"></i>
                                    Export PDF
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-cog me-2"></i>Quick Actions</h6>
                            <div class="d-grid gap-2 d-md-block">
                                <a href="{{ route('user.checkin.staff-scanner', $gym->slug) }}" class="btn btn-success">
                                    <i class="fas fa-camera me-2"></i>
                                    Open Scanner
                                </a>
                                <a href="{{ route('user.home', $gym->slug) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home me-2"></i>
                                    Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadRecentCheckins();
    createCharts();
});

function createCharts() {
    // Check-in Types Chart
    const typesCtx = document.getElementById('checkinTypesChart').getContext('2d');
    new Chart(typesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Self Scans', 'Gate Scans'],
            datasets: [{
                data: [{{ $stats['self_scans'] }}, {{ $stats['gate_scans'] }}],
                backgroundColor: ['#17a2b8', '#ffc107'],
                borderWidth: 2,
                borderColor: '#fff'
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

    // Daily Trend Chart (placeholder - you can load real data via AJAX)
    const trendCtx = document.getElementById('dailyTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Check-ins',
                data: [12, 19, 15, 25, 22, 30, 28],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function loadRecentCheckins() {
    fetch('{{ route("user.checkin.history", $gym->slug) }}?limit=10')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('recent-checkins-table');
            
            if (data.data.history.checkins.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No recent check-ins found</p>
                    </div>
                `;
                return;
            }

            let tableHTML = `
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Type</th>
                                <th>Branch</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.data.history.checkins.forEach(checkin => {
                tableHTML += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                    <span class="text-white fw-bold">${checkin.user.name.charAt(0)}</span>
                                </div>
                            </div>
                            <strong>${checkin.user.name}</strong>
                        </td>
                        <td>
                            <span class="badge bg-${checkin.checkin_type === 'self_scan' ? 'primary' : 'success'}">
                                <i class="fas fa-${checkin.checkin_type === 'self_scan' ? 'mobile-alt' : 'camera'} me-1"></i>
                                ${checkin.checkin_type_label}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">${checkin.branch ? checkin.branch.name : 'Main Location'}</span>
                        </td>
                        <td>
                            <div>
                                <strong>${new Date(checkin.created_at).toLocaleDateString()}</strong>
                                <br>
                                <small class="text-muted">${new Date(checkin.created_at).toLocaleTimeString()}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>
                                Successful
                            </span>
                        </td>
                    </tr>
                `;
            });

            tableHTML += `
                        </tbody>
                    </table>
                </div>
            `;

            container.innerHTML = tableHTML;
        })
        .catch(error => {
            console.error('Error loading recent check-ins:', error);
            document.getElementById('recent-checkins-table').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                    <p class="text-muted">Failed to load recent check-ins</p>
                </div>
            `;
        });
}

function exportData(format) {
    // Implement export functionality
    alert(`Export ${format.toUpperCase()} functionality will be implemented here.`);
}
</script>
@endpush
@endsection
