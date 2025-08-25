<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #495057;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #495057;
            margin: 0;
            font-size: 28px;
        }
        .gym-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .gym-info h2 {
            color: #495057;
            margin-top: 0;
            font-size: 18px;
        }
        .gym-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .gym-info td {
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .gym-info td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #8B5CF6;
            border-bottom: 1px solid #8B5CF6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #8B5CF6;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #8B5CF6;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .data-table th {
            background: #8B5CF6;
            color: white;
            font-weight: bold;
        }
        .data-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .chart-placeholder {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        @media print {
            .section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gym Report</h1>
        <p>Generated on {{ $generated_at }}</p>
    </div>

    <div class="gym-info">
        <h2>Gym Information</h2>
        <table>
            <tr>
                <td>Name:</td>
                <td>{{ $gym_info['name'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Address:</td>
                <td>{{ $gym_info['address'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td>{{ $gym_info['phone'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $gym_info['email'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Report Period:</td>
                <td>{{ $date_range['from'] ?? 'N/A' }} to {{ $date_range['to'] ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    @if(isset($memberships))
    <div class="section">
        <h2>Membership Analysis</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($memberships['total_memberships'] ?? 0) }}</div>
                <div class="stat-label">Total Memberships</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${{ number_format($memberships['total_revenue'] ?? 0, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${{ number_format($memberships['average_price'] ?? 0, 2) }}</div>
                <div class="stat-label">Average Price</div>
            </div>
        </div>

        @if(isset($memberships['period_distribution']))
        <h3>Period Distribution</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Count</th>
                    <th>Total Revenue</th>
                    <th>Average Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($memberships['period_distribution'] as $period => $stats)
                <tr>
                    <td>{{ ucfirst($period) }}</td>
                    <td>{{ number_format($stats['count']) }}</td>
                    <td>${{ number_format($stats['total_revenue'], 2) }}</td>
                    <td>${{ number_format($stats['average_price'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($payments))
    <div class="section">
        <h2>Payment Analysis</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($payments['total_payments'] ?? 0) }}</div>
                <div class="stat-label">Total Payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${{ number_format($payments['total_revenue'] ?? 0, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($payments['failed_payments'] ?? 0) }}</div>
                <div class="stat-label">Failed Payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($payments['pending_payments'] ?? 0) }}</div>
                <div class="stat-label">Pending Payments</div>
            </div>
        </div>

        @if(isset($payments['status_distribution']))
        <h3>Payment Status Distribution</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Total Amount</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments['status_distribution'] as $status => $stats)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <td>{{ number_format($stats['count']) }}</td>
                    <td>${{ number_format($stats['total_amount'], 2) }}</td>
                    <td>{{ $stats['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($subscriptions))
    <div class="section">
        <h2>Subscription Analysis</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($subscriptions['total_subscriptions'] ?? 0) }}</div>
                <div class="stat-label">Total Subscriptions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($subscriptions['active_subscriptions'] ?? 0) }}</div>
                <div class="stat-label">Active Subscriptions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($subscriptions['expired_subscriptions'] ?? 0) }}</div>
                <div class="stat-label">Expired Subscriptions</div>
            </div>
        </div>

        @if(isset($subscriptions['status_distribution']))
        <h3>Subscription Status Distribution</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions['status_distribution'] as $status => $stats)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <td>{{ number_format($stats['count']) }}</td>
                    <td>{{ $stats['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($invitations))
    <div class="section">
        <h2>Invitation Analysis</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($invitations['total_invitations'] ?? 0) }}</div>
                <div class="stat-label">Total Invitations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($invitations['accepted_invitations'] ?? 0) }}</div>
                <div class="stat-label">Accepted Invitations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($invitations['pending_invitations'] ?? 0) }}</div>
                <div class="stat-label">Pending Invitations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($invitations['rejected_invitations'] ?? 0) }}</div>
                <div class="stat-label">Rejected Invitations</div>
            </div>
        </div>

        @if(isset($invitations['status_distribution']))
        <h3>Invitation Status Distribution</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invitations['status_distribution'] as $status => $stats)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <td>{{ number_format($stats['count']) }}</td>
                    <td>{{ $stats['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($users))
    <div class="section">
        <h2>User Analysis</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($users['total_users'] ?? 0) }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($users['new_users'] ?? 0) }}</div>
                <div class="stat-label">New Users (30 days)</div>
            </div>
        </div>

        @if(isset($users['gender_distribution']))
        <h3>Gender Distribution</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Gender</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users['gender_distribution'] as $gender => $stats)
                <tr>
                    <td>{{ ucfirst($gender) }}</td>
                    <td>{{ number_format($stats['count']) }}</td>
                    <td>{{ $stats['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($classes_services))
    <div class="section">
        <h2>Classes & Services Analysis</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($classes_services['total_services'] ?? 0) }}</div>
                <div class="stat-label">Total Services</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($classes_services['total_classes'] ?? 0) }}</div>
                <div class="stat-label">Total Classes</div>
            </div>
        </div>

        @if(isset($classes_services['most_booked_services']))
        <h3>Most Booked Services</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Bookings</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes_services['most_booked_services'] as $service)
                <tr>
                    <td>{{ $service->name ?? 'Unknown' }}</td>
                    <td>{{ $service->bookings->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if(isset($classes_services['most_booked_classes']))
        <h3>Most Booked Classes</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Class Name</th>
                    <th>Bookings</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes_services['most_booked_classes'] as $class)
                <tr>
                    <td>{{ $class->name ?? 'Unknown' }}</td>
                    <td>{{ $class->bookings->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($score_analysis))
    <div class="section">
        <h2>Score Analysis</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($score_analysis['total_scores'] ?? 0) }}</div>
                <div class="stat-label">Total Scores</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($score_analysis['average_score'] ?? 0, 1) }}</div>
                <div class="stat-label">Average Score</div>
            </div>
        </div>

        @if(isset($score_analysis['score_distribution']))
        <h3>Score Distribution</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Score Range</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($score_analysis['score_distribution'] as $range => $count)
                <tr>
                    <td>{{ ucfirst($range) }}</td>
                    <td>{{ number_format($count) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($trainer_insights))
    <div class="section">
        <h2>Trainer Insights</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($trainer_insights['total_trainers'] ?? 0) }}</div>
                <div class="stat-label">Total Trainers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($trainer_insights['total_sessions'] ?? 0) }}</div>
                <div class="stat-label">Total Sessions</div>
            </div>
        </div>

        @if(isset($trainer_insights['top_trainers']))
        <h3>Top Trainers</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Trainer Name</th>
                    <th>Sessions</th>
                    <th>Specialization</th>
                    <th>Experience (Years)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trainer_insights['top_trainers'] as $trainer)
                <tr>
                    <td>{{ $trainer['name'] }}</td>
                    <td>{{ number_format($trainer['sessions_count']) }}</td>
                    <td>{{ $trainer['specialization'] ?? 'N/A' }}</td>
                    <td>{{ $trainer['experience_years'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($page_views))
    <div class="section">
        <h2>Page Views Analysis</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($page_views['total_page_views'] ?? 0) }}</div>
                <div class="stat-label">Total Page Views</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($page_views['unique_visitors'] ?? 0) }}</div>
                <div class="stat-label">Unique Visitors</div>
            </div>
        </div>

        @if(isset($page_views['most_viewed_pages']) && count($page_views['most_viewed_pages']) > 0)
        <h3>Most Viewed Pages</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Views</th>
                </tr>
            </thead>
            <tbody>
                @foreach($page_views['most_viewed_pages'] as $page => $views)
                <tr>
                    <td>{{ $page }}</td>
                    <td>{{ number_format($views) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Gym Management System</p>
        <p>For questions or support, please contact your system administrator</p>
    </div>
</body>
</html>
