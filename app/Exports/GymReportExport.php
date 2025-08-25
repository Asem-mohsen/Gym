<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GymReportExport implements FromArray, WithMultipleSheets
{
    protected $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function sheets(): array
    {
        $sheets = [
            'Overview' => new OverviewSheet($this->reportData),
        ];

        // Add sheets for each section
        if (isset($this->reportData['memberships'])) {
            $sheets['Memberships'] = new MembershipsSheet($this->reportData['memberships']);
        }

        if (isset($this->reportData['payments'])) {
            $sheets['Payments'] = new PaymentsSheet($this->reportData['payments']);
        }

        if (isset($this->reportData['users'])) {
            $sheets['Users'] = new UsersSheet($this->reportData['users']);
        }

        if (isset($this->reportData['classes_services'])) {
            $sheets['Classes_Services'] = new ClassesServicesSheet($this->reportData['classes_services']);
        }

        if (isset($this->reportData['score_analysis'])) {
            $sheets['Score_Analysis'] = new ScoreAnalysisSheet($this->reportData['score_analysis']);
        }

        if (isset($this->reportData['trainer_insights'])) {
            $sheets['Trainer_Insights'] = new TrainerInsightsSheet($this->reportData['trainer_insights']);
        }

        return $sheets;
    }

    public function array(): array
    {
        return [];
    }
}

class OverviewSheet implements FromArray, WithTitle, WithHeadings
{
    protected $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function array(): array
    {
        $data = [
            ['Gym Report Overview'],
            [''],
            ['Gym Information'],
            ['Name', $this->reportData['gym_info']['name'] ?? 'N/A'],
            ['Address', $this->reportData['gym_info']['address'] ?? 'N/A'],
            ['Phone', $this->reportData['gym_info']['phone'] ?? 'N/A'],
            ['Email', $this->reportData['gym_info']['email'] ?? 'N/A'],
            [''],
            ['Report Period'],
            ['From', $this->reportData['date_range']['from'] ?? 'N/A'],
            ['To', $this->reportData['date_range']['to'] ?? 'N/A'],
            ['Generated At', $this->reportData['generated_at'] ?? 'N/A'],
            [''],
            ['Summary Statistics'],
        ];

        // Add summary statistics for each section
        if (isset($this->reportData['memberships'])) {
            $data[] = ['Total Memberships', $this->reportData['memberships']['total_memberships'] ?? 0];
            $data[] = ['Total Revenue', $this->reportData['memberships']['total_revenue'] ?? 0];
        }

        if (isset($this->reportData['payments'])) {
            $data[] = ['Total Payments', $this->reportData['payments']['total_payments'] ?? 0];
            $data[] = ['Total Revenue', $this->reportData['payments']['total_revenue'] ?? 0];
        }

        if (isset($this->reportData['users'])) {
            $data[] = ['Total Users', $this->reportData['users']['total_users'] ?? 0];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Overview';
    }

    public function headings(): array
    {
        return [];
    }
}

class MembershipsSheet implements FromArray, WithTitle, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['Membership Statistics'],
            ['Total Memberships', $this->data['total_memberships'] ?? 0],
            ['Total Revenue', $this->data['total_revenue'] ?? 0],
            ['Average Price', $this->data['average_price'] ?? 0],
            [''],
            ['Period Distribution'],
        ];

        if (isset($this->data['period_distribution'])) {
            foreach ($this->data['period_distribution'] as $period => $stats) {
                $rows[] = [ucfirst($period), $stats['count'], $stats['total_revenue'], $stats['average_price']];
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Memberships';
    }

    public function headings(): array
    {
        return [];
    }
}

class PaymentsSheet implements FromArray, WithTitle, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['Payment Statistics'],
            ['Total Payments', $this->data['total_payments'] ?? 0],
            ['Total Revenue', $this->data['total_revenue'] ?? 0],
            ['Failed Payments', $this->data['failed_payments'] ?? 0],
            ['Pending Payments', $this->data['pending_payments'] ?? 0],
            [''],
            ['Status Distribution'],
        ];

        if (isset($this->data['status_distribution'])) {
            foreach ($this->data['status_distribution'] as $status => $stats) {
                $rows[] = [ucfirst($status), $stats['count'], $stats['total_amount'], $stats['percentage'] . '%'];
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Payments';
    }

    public function headings(): array
    {
        return [];
    }
}

class UsersSheet implements FromArray, WithTitle, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['User Statistics'],
            ['Total Users', $this->data['total_users'] ?? 0],
            ['New Users (30 days)', $this->data['new_users'] ?? 0],
            [''],
            ['Gender Distribution'],
        ];

        if (isset($this->data['gender_distribution'])) {
            foreach ($this->data['gender_distribution'] as $gender => $stats) {
                $rows[] = [ucfirst($gender), $stats['count'], $stats['percentage'] . '%'];
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Users';
    }

    public function headings(): array
    {
        return [];
    }
}

class ClassesServicesSheet implements FromArray, WithTitle, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['Classes & Services Statistics'],
            ['Total Services', $this->data['total_services'] ?? 0],
            ['Total Classes', $this->data['total_classes'] ?? 0],
            [''],
            ['Most Booked Services'],
        ];

        if (isset($this->data['most_booked_services'])) {
            foreach ($this->data['most_booked_services'] as $service) {
                $rows[] = [$service->name ?? 'Unknown', $service->bookings->count()];
            }
        }

        $rows[] = [''];
        $rows[] = ['Most Booked Classes'];

        if (isset($this->data['most_booked_classes'])) {
            foreach ($this->data['most_booked_classes'] as $class) {
                $rows[] = [$class->name ?? 'Unknown', $class->bookings->count()];
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Classes_Services';
    }

    public function headings(): array
    {
        return [];
    }
}

class ScoreAnalysisSheet implements FromArray, WithTitle, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['Score Analysis'],
            ['Total Scores', $this->data['total_scores'] ?? 0],
            ['Average Score', $this->data['average_score'] ?? 0],
            [''],
            ['Score Distribution'],
        ];

        if (isset($this->data['score_distribution'])) {
            foreach ($this->data['score_distribution'] as $range => $count) {
                $rows[] = [ucfirst($range), $count];
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Score_Analysis';
    }

    public function headings(): array
    {
        return [];
    }
}

class TrainerInsightsSheet implements FromArray, WithTitle, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['Trainer Insights'],
            ['Total Trainers', $this->data['total_trainers'] ?? 0],
            ['Total Sessions', $this->data['total_sessions'] ?? 0],
            [''],
            ['Top Trainers'],
        ];

        if (isset($this->data['top_trainers'])) {
            foreach ($this->data['top_trainers'] as $trainer) {
                $rows[] = [
                    $trainer['name'],
                    $trainer['sessions_count'],
                    $trainer['specialization'] ?? 'N/A',
                    $trainer['experience_years'] ?? 'N/A'
                ];
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Trainer_Insights';
    }

    public function headings(): array
    {
        return [];
    }
}
