<?php

namespace App\Services\Reports;

use App\Models\SiteSetting;
use App\Models\Membership;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ClassModel;
use App\Models\Invitation;
use App\Models\Booking;
use App\Models\BranchScore;
use App\Models\TrainerInformation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GymReportExport;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    protected $gym;
    protected $dateFrom;
    protected $dateTo;
    protected $sections;

    public function generateReport(array $data): bool
    {
        try {
            $this->gym = SiteSetting::find($data['gym_id']);
            $this->dateFrom = Carbon::parse($data['date_from']);
            $this->dateTo = Carbon::parse($data['date_to']);
            $this->sections = $data['report_sections'];

            $reportData = $this->collectReportData();
            $this->storeReportData($reportData);

            $this->saveReportAsDocument($reportData, $data['export_format'] ?? 'pdf');

            return true;
        } catch (\Exception $e) {
            Log::error('Report generation failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function collectReportData(): array
    {
        $data = [
            'gym_info' => $this->getGymInfo(),
            'date_range' => [
                'from' => $this->dateFrom->format('Y-m-d'),
                'to' => $this->dateTo->format('Y-m-d'),
            ],
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];

        foreach ($this->sections as $section) {
            $method = 'get' . ucfirst($section) . 'Data';
            if (method_exists($this, $method)) {
                $data[$section] = $this->$method();
            }
        }

        return $data;
    }

    protected function getGymInfo(): array
    {
        return [
            'name' => $this->gym->gym_name,
            'address' => $this->gym->address,
            'phone' => $this->gym->phone,
            'email' => $this->gym->email,
        ];
    }

    protected function getMembershipsData(): array
    {
        $memberships = Membership::where('site_setting_id', $this->gym->id)
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->with(['payment', 'features'])
            ->get();

        $periodStats = $memberships->groupBy('period')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total_revenue' => $group->sum('price'),
                'average_price' => $group->avg('price'),
            ];
        });

        $monthlyStats = $memberships->groupBy(function ($membership) {
            return $membership->created_at->format('Y-m');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
            ];
        });

        return [
            'total_memberships' => $memberships->count(),
            'total_revenue' => $memberships->sum('price'),
            'average_price' => $memberships->avg('price'),
            'period_distribution' => $periodStats,
            'monthly_trend' => $monthlyStats,
            'memberships' => $memberships->take(50), // Limit for performance
        ];
    }

    protected function getSubscriptionsData(): array
    {
        // Get subscriptions through branches that belong to this gym
        $subscriptions = Subscription::whereHas('branch', function ($query) {
            $query->where('site_setting_id', $this->gym->id);
        })
        ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
        ->with(['user', 'membership', 'branch'])
        ->get();

        $statusStats = $subscriptions->groupBy('status')->map(function ($group) use ($subscriptions) {
            return [
                'count' => $group->count(),
                'percentage' => round(($group->count() / $subscriptions->count()) * 100, 2),
            ];
        });

        return [
            'total_subscriptions' => $subscriptions->count(),
            'active_subscriptions' => $subscriptions->where('status', 'active')->count(),
            'expired_subscriptions' => $subscriptions->where('status', 'expired')->count(),
            'status_distribution' => $statusStats,
            'subscriptions' => $subscriptions->take(50),
        ];
    }

    protected function getUsersData(): array
    {
        $users = User::whereHas('gyms', function ($query) {
            $query->where('site_setting_id', $this->gym->id);
        })
        ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
        ->get();

        $genderStats = $users->groupBy('gender')->map(function ($group) use ($users) {
            return [
                'count' => $group->count(),
                'percentage' => round(($group->count() / $users->count()) * 100, 2),
            ];
        });

        $monthlyRegistrations = $users->groupBy(function ($user) {
            return $user->created_at->format('Y-m');
        })->map(function ($group) {
            return $group->count();
        });

        return [
            'total_users' => $users->count(),
            'new_users' => $users->where('created_at', '>=', now()->subDays(30))->count(),
            'gender_distribution' => $genderStats,
            'monthly_registrations' => $monthlyRegistrations,
            'users' => $users->take(50),
        ];
    }

    protected function getPaymentsData(): array
    {
        // Get payments through branches that belong to this gym
        $payments = Payment::whereHas('branch', function ($query) {
            $query->where('site_setting_id', $this->gym->id);
        })
        ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
        ->get();

        $statusStats = $payments->groupBy('status')->map(function ($group) use ($payments) {
            return [
                'count' => $group->count(),
                'total_amount' => $group->sum('amount'),
                'percentage' => round(($group->count() / $payments->count()) * 100, 2),
            ];
        });

        $paymentMethodStats = $payments->groupBy('payment_method')->map(function ($group) use ($payments) {
            return [
                'count' => $group->count(),
                'total_amount' => $group->sum('amount'),
                'percentage' => round(($group->count() / $payments->count()) * 100, 2),
            ];
        });

        $monthlyPayments = $payments->where('status', 'completed')
            ->groupBy(function ($payment) {
                return $payment->created_at->format('Y-m');
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_amount' => $group->sum('amount'),
                ];
            });

        return [
            'total_payments' => $payments->count(),
            'total_revenue' => $payments->where('status', 'completed')->sum('amount'),
            'failed_payments' => $payments->where('status', 'failed')->count(),
            'pending_payments' => $payments->where('status', 'pending')->count(),
            'status_distribution' => $statusStats,
            'payment_method_distribution' => $paymentMethodStats,
            'monthly_revenue' => $monthlyPayments,
            'payments' => $payments->take(50),
        ];
    }

    protected function getInvitationsData(): array
    {
        $invitations = Invitation::where('site_setting_id', $this->gym->id)
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->get();

        $statusStats = $invitations->groupBy('status')->map(function ($group) use ($invitations) {
            return [
                'count' => $group->count(),
                'percentage' => round(($group->count() / $invitations->count()) * 100, 2),
            ];
        });

        return [
            'total_invitations' => $invitations->count(),
            'accepted_invitations' => $invitations->where('status', 'accepted')->count(),
            'pending_invitations' => $invitations->where('status', 'pending')->count(),
            'rejected_invitations' => $invitations->where('status', 'rejected')->count(),
            'status_distribution' => $statusStats,
            'invitations' => $invitations->take(50),
        ];
    }

    protected function getScoreAnalysisData(): array
    {
        $scores = BranchScore::whereHas('branch', function ($query) {
            $query->where('site_setting_id', $this->gym->id);
        })
        ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
        ->with(['branch', 'scoreItems'])
        ->get();

        $averageScore = $scores->avg('score');
        $scoreRanges = [
            'excellent' => $scores->where('score', '>=', 90)->count(),
            'good' => $scores->whereBetween('score', [70, 89])->count(),
            'average' => $scores->whereBetween('score', [50, 69])->count(),
            'poor' => $scores->where('score', '<', 50)->count(),
        ];

        return [
            'total_scores' => $scores->count(),
            'average_score' => round($averageScore, 2),
            'score_distribution' => $scoreRanges,
            'scores' => $scores->take(50),
        ];
    }

    protected function getClassesServicesData(): array
    {
        $services = Service::where('site_setting_id', $this->gym->id)
            ->with(['bookings' => function ($query) {
                $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
            }])
            ->get();

        $classes = ClassModel::where('site_setting_id', $this->gym->id)
            ->with(['bookings' => function ($query) {
                $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
            }])
            ->get();

        $mostBookedServices = $services->sortByDesc(function ($service) {
            return $service->bookings->count();
        })->take(10);

        $mostBookedClasses = $classes->sortByDesc(function ($class) {
            return $class->bookings->count();
        })->take(10);

        return [
            'total_services' => $services->count(),
            'total_classes' => $classes->count(),
            'most_booked_services' => $mostBookedServices,
            'most_booked_classes' => $mostBookedClasses,
            'services' => $services->take(50),
            'classes' => $classes->take(50),
        ];
    }

    protected function getTrainerInsightsData(): array
    {
        $trainers = TrainerInformation::whereHas('user.gyms', function ($query) {
            $query->where('site_setting_id', $this->gym->id);
        })
        ->with(['user', 'coachingSessions' => function ($query) {
            $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
        }])
        ->get();

        $trainerStats = $trainers->map(function ($trainer) {
            return [
                'name' => $trainer->user->name ?? 'Unknown',
                'sessions_count' => $trainer->coachingSessions->count(),
                'specialization' => $trainer->specialization,
                'experience_years' => $trainer->experience_years,
            ];
        })->sortByDesc('sessions_count');

        return [
            'total_trainers' => $trainers->count(),
            'total_sessions' => $trainers->sum(function ($trainer) {
                return $trainer->coachingSessions->count();
            }),
            'top_trainers' => $trainerStats->take(10),
            'trainers' => $trainers->take(50),
        ];
    }

    protected function getPageViewsData(): array
    {
        // For now, we'll return a placeholder since page views tracking might not be implemented
        // You can implement this based on your analytics system
        return [
            'total_page_views' => 0,
            'most_viewed_pages' => [],
            'page_views_by_date' => [],
            'unique_visitors' => 0,
        ];
    }

    protected function storeReportData(array $data): void
    {
        // Store report data in cache for quick access
        $reportKey = 'gym_report_' . $this->gym->id . '_' . now()->timestamp;
        cache()->put($reportKey, $data, now()->addHours(24));
        
        // Store report metadata
        session(['last_report_key' => $reportKey]);
    }

    public function downloadReport($record, string $format): bool
    {
        try {
            $reportKey = session('last_report_key');
            $reportData = cache()->get($reportKey);

            if (!$reportData) {
                return false;
            }

            if ($format === 'pdf') {
                return $this->generatePdfReport($reportData);
            } elseif ($format === 'excel') {
                return $this->generateExcelReport($reportData);
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Report download failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function generatePdfReport(array $data): bool
    {
        try {
            $pdf = PDF::loadView('exports.gym-report-pdf', $data);
            $filename = 'gym_report_' . $this->gym->id . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            
            Storage::disk('public')->put('reports/' . $filename, $pdf->output());
            
            return true;
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function generateExcelReport(array $data): bool
    {
        try {
            $filename = 'gym_report_' . $this->gym->id . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            
            Excel::store(new GymReportExport($data), 'reports/' . $filename, 'public');
            
            return true;
        } catch (\Exception $e) {
            Log::error('Excel generation failed: ' . $e->getMessage());
            return false;
        }
    }

    public function regenerateReport($record): bool
    {
        return true;
    }

    protected function saveReportAsDocument(array $reportData, string $format): void
    {
        try {
            $gymName = $reportData['gym_info']['name'];
            $dateRange = $reportData['date_range']['from'] . ' to ' . $reportData['date_range']['to'];
            $sections = implode(', ', array_map('ucfirst', $this->sections));

            // Create document record
            $document = Document::create([
                'title' => "Gym Report - {$gymName} ({$dateRange})",
                'description' => "Comprehensive gym report for {$gymName} covering {$sections} from {$dateRange}",
                'document_type' => 'report',
                'is_active' => true,
                'is_internal' => false,
                'published_at' => now(),
                'created_by_id' => Auth::id(),
            ]);

            // Generate and save the files based on format
            if ($format === 'pdf') {
                $this->generateAndSavePdfDocument($document, $reportData);
            } elseif ($format === 'excel') {
                $this->generateAndSaveExcelDocument($document, $reportData);
            } elseif ($format === 'both') {
                $this->generateAndSavePdfDocument($document, $reportData);
                $this->generateAndSaveExcelDocument($document, $reportData);
            }

            // Attach to the specific gym
            $document->siteSettings()->attach($this->gym->id);

        } catch (\Exception $e) {
            Log::error('Failed to save report as document: ' . $e->getMessage());
        }
    }

    protected function generateAndSavePdfDocument(Document $document, array $reportData): void
    {
        try {
            $pdf = PDF::loadView('exports.gym-report-pdf', $reportData);
            $filename = 'gym_report_' . $reportData['gym_info']['name'] . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            
            // Save to document media collection
            $document->addMediaFromString($pdf->output())
                ->usingName($filename)
                ->usingFileName($filename)
                ->toMediaCollection('document');
                
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF document: ' . $e->getMessage());
        }
    }

    protected function generateAndSaveExcelDocument(Document $document, array $reportData): void
    {
        try {
            $filename = 'gym_report_' . $reportData['gym_info']['name'] . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            
            // Generate Excel file directly to string
            $excelContent = Excel::raw(new GymReportExport($reportData), \Maatwebsite\Excel\Excel::XLSX);
            
            // Add to document media collection from string
            $document->addMediaFromString($excelContent)
                ->usingName($filename)
                ->usingFileName($filename)
                ->toMediaCollection('document');
                
        } catch (\Exception $e) {
            Log::error('Failed to generate Excel document: ' . $e->getMessage());
        }
    }
}
