<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\GymDataImportService;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Auth, Log};
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TemplateExport;
use App\Http\Requests\Import\ImportGymDataRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GymDataImportController extends Controller
{
    /**
     * Show the import form
     */
    public function index()
    {
        $template = GymDataImportService::getImportTemplate();
        
        return view('admin.import.index', compact('template'));
    }

    /**
     * Handle the import process
     */
    public function import(ImportGymDataRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            /**
             * @var User $user
             */
            $user = Auth::user();
            
            $siteSetting = $user->getCurrentSite();

            $siteSettingId = $siteSetting->id;

            $validationErrors = GymDataImportService::validateImportFile($data['import_file']);

            if (!empty($validationErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File validation failed',
                    'errors' => $validationErrors
                ], 422);
            }

            // Perform import
            $importService = new GymDataImportService($siteSettingId);

            $results = $importService->importGymData($data['import_file']);

            $hasErrors = $results['summary']['total_errors'] > 0;
            
            $validationErrors = [];
            
            foreach (['users', 'branches', 'memberships', 'classes', 'services'] as $type) {
                if (isset($results[$type]['errors']) && !empty($results[$type]['errors'])) {
                    Log::info("Errors for $type:", $results[$type]['errors']);
                    foreach ($results[$type]['errors'] as $error) {
                        $validationErrors[] = ucfirst($type) . ': ' . $error;
                    }
                }
            }
            
            if (isset($results['errors']) && !empty($results['errors'])) {
                Log::info("General errors:", $results['errors']);
                foreach ($results['errors'] as $error) {
                    $validationErrors[] = $error['sheet'] . ': ' . $error['error'];
                }
            }
            
            return response()->json([
                'success' => !$hasErrors,
                'message' => $hasErrors ? 'Import completed with errors' : 'Import completed successfully',
                'data' => $results,
                'errors' => $validationErrors
            ]);

        } catch (\Exception $e) {
            Log::error('Gym data import failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'file' => $request->file('import_file')?->getClientOriginalName()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $templateData = [
            'Users' => [
                ['name', 'email', 'phone', 'address', 'gender', 'role', 'status'],
                ['John Doe', 'john.doe@example.com', '1234567890', '123 Main Street', 'male', 'regular_user', '1'],
                ['Jane Smith', 'jane.smith@example.com', '0987654321', '456 Oak Avenue', 'female', 'trainer', '1'],
                ['Mike Johnson', 'mike.johnson@example.com', '5551234567', '789 Pine Road', 'male', 'admin', '1'],
                ['Jake Johnson', 'jake.johnson@example.com', '5551234567', '789 Pine Road', 'male', 'admin', '1'],
                ['John Johnson', 'John.johnson@example.com', '5551234567', '789 Pine Road', 'male', 'management', '1'],
                ['Sophia Johnson', 'Sophia.johnson12@example.com', '15551234567', '789 Pine Road', 'female', 'sales', '1'],
                ['Mark Johnson', 'farouk@example.com', '5551234567', '789 Pine Road', 'female', 'regular_user', '1'],
                ['Nevin Johnson', 'Nevine@example.com', '5551234567', '789 Pine Road', 'male', 'sales', '1'],
            ],
            'Branches' => [
                ['name', 'name_en', 'name_ar', 'location', 'location_en', 'location_ar', 'type', 'size', 'manager_email'],
                ['Main Branch', 'Main Branch', 'الفرع الرئيسي', 'Downtown Area', 'Downtown Area', 'منطقة وسط المدينة', 'mix', '1000', 'jake.johnson@example.com'],
                ['Women Branch', 'Women Branch', 'فرع السيدات', 'Shopping District', 'Shopping District', 'منطقة التسوق', 'women', '800', 'mike.johnson@example.com'],
            ],
            'Memberships' => [
                ['name', 'name_en', 'name_ar', 'period', 'description', 'subtitle', 'price', 'billing_interval', 'status', 'order'],
                ['Basic Plan', 'Basic Plan', 'الخطة الأساسية', '1 month', 'Basic gym membership with access to all facilities', 'Perfect for beginners', '50.00', 'monthly', '1', '1'],
                ['Premium Plan', 'Premium Plan', 'الخطة المميزة', '3 months', 'Premium membership with personal training sessions', 'Best value for money', '150.00', 'monthly', '1', '2'],
                ['Annual Plan', 'Annual Plan', 'الخطة السنوية', '12 months', 'Annual membership with maximum benefits', 'Long-term commitment', '500.00', 'yearly', '1', '3'],
            ],
            'Classes' => [
                ['name', 'name_en', 'type', 'description', 'status', 'trainer_emails'],
                ['Yoga Class', 'Yoga Class', 'fitness', 'Relaxing yoga session for all levels', 'active', 'jane.smith@example.com'],
                ['Cardio Training', 'Cardio Training', 'fitness', 'High-intensity cardio workout', 'active', 'jane.smith@example.com'],
                ['Strength Training', 'Strength Training', 'fitness', 'Weight training and muscle building', 'active', 'jane.smith@example.com'],
            ],
            'Services' => [
                ['name', 'name_en', 'name_ar', 'description', 'duration', 'price', 'requires_payment', 'booking_type', 'is_available'],
                ['Personal Training', 'Personal Training', 'تدريب شخصي', 'One-on-one personal training session', '60', '100.00', '1', 'paid_booking', '1'],
                ['Nutrition Consultation', 'Nutrition Consultation', 'استشارة تغذية', 'Professional nutrition advice and meal planning', '45', '75.00', '1', 'paid_booking', '1'],
                ['Fitness Assessment', 'Fitness Assessment', 'تقييم اللياقة', 'Comprehensive fitness evaluation and goal setting', '30', '50.00', '1', 'paid_booking', '1'],
            ]
        ];

        $export = new TemplateExport($templateData);
        
        $fileName = 'gym_import_template_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $fileName);
    }

    /**
     * Get import status and results
     */
    public function getImportStatus(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Import status endpoint'
        ]);
    }

    /**
     * Show import history
     */
    public function history()
    {
        return view('admin.import.history');
    }
}
