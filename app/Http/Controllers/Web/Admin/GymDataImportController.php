<?php

namespace App\Http\Controllers\Web\Admin;

use Exception;
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
            
            $allErrors = [];
            
            // Collect all errors from each sheet type
            foreach (['users', 'branches', 'memberships', 'membership_features', 'classes', 'class_schedules', 'class_pricing', 'services', 'subscriptions'] as $type) {
                if (isset($results[$type]['errors']) && !empty($results[$type]['errors'])) {
                    Log::info("Errors for $type:", $results[$type]['errors']);
                    foreach ($results[$type]['errors'] as $error) {
                        $allErrors[] = ucfirst(str_replace('_', ' ', $type)) . ': ' . $error;
                    }
                }
            }
            
            // Collect general errors
            if (isset($results['errors']) && !empty($results['errors'])) {
                Log::info("General errors:", $results['errors']);
                foreach ($results['errors'] as $error) {
                    $allErrors[] = $error['sheet'] . ': ' . $error['error'];
                }
            }
            
            // If there are errors, show all of them
            if ($hasErrors) {
                $errorMessage = 'Import completed with ' . count($allErrors) . ' errors:';
                if (count($allErrors) > 10) {
                    $errorMessage .= ' (showing first 10)';
                    $displayErrors = array_slice($allErrors, 0, 10);
                } else {
                    $displayErrors = $allErrors;
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'data' => $results,
                    'errors' => $displayErrors,
                    'total_errors' => count($allErrors),
                    'all_errors' => $allErrors
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Import validation completed successfully (no data inserted - insertion is commented out)',
                'data' => $results,
                'errors' => []
            ]);

        } catch (Exception $e) {
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
                [
                    'name (Required)', 
                    'email (Required, Unique)', 
                    'phone (Required)', 
                    'address (Required)', 
                    'gender (Required: male or female only)', 
                    'role (Required: regular_user, admin, trainer, staff, management)', 
                    'status (Required: 1=active, 0=inactive)'
                ],
                ['John Doe', 'john.doe@example.com', '1234567890', '123 Main Street', 'male', 'regular_user', '1'],
                ['Jane Smith', 'jane.smith@example.com', '0987654321', '456 Oak Avenue', 'female', 'trainer', '1'],
                ['Mike Johnson', 'mike.johnson@example.com', '5551234567', '789 Pine Road', 'male', 'admin', '1'],
                ['Jake Johnson', 'jake.johnson@example.com', '5551234567', '789 Pine Road', 'male', 'admin', '1'],
                ['John Johnson', 'John.johnson@example.com', '5551234567', '789 Pine Road', 'male', 'management', '1'],
                ['Sophia Johnson', 'Sophia.johnson12@example.com', '15551234567', '789 Pine Road', 'female', 'staff', '1'],
                ['Mark Johnson', 'farouk@example.com', '5551234567', '789 Pine Road', 'female', 'regular_user', '1'],
                ['Nevin Johnson', 'Nevine@example.com', '5551234567', '789 Pine Road', 'male', 'staff', '1'],
            ],
            'Branches' => [
                [
                    'name (Required)', 
                    'name_en (Required)', 
                    'name_ar (Required)', 
                    'location (Required)', 
                    'location_en (Required)', 
                    'location_ar (Required)', 
                    'type (Required)', 
                    'size (Required)', 
                    'manager_email (Required, must exist in Users sheet with admin role)'
                ],
                ['Main Branch', 'Main Branch', 'الفرع الرئيسي', 'Downtown Area', 'Downtown Area', 'منطقة وسط المدينة', 'mix', '1000', 'jake.johnson@example.com'],
                ['Women Branch', 'Women Branch', 'فرع السيدات', 'Shopping District', 'Shopping District', 'منطقة التسوق', 'women', '800', 'mike.johnson@example.com'],
            ],
            'Memberships' => [
                [
                    'name (Required)', 
                    'name_en (Required)', 
                    'name_ar (Required)', 
                    'period (Required)', 
                    'description (Required)', 
                    'subtitle (Required)', 
                    'price (Required)', 
                    'billing_interval (Required: monthly or yearly)', 
                    'status (Required: 1=active, 0=inactive)', 
                    'order (Required)', 
                    'invitation_limit (Required, number of invitations allowed)'
                ],
                ['Basic Plan', 'Basic Plan', 'الخطة الأساسية', '1 month', 'Basic gym membership with access to all facilities', 'Perfect for beginners', '50.00', 'monthly', '1', '1', '5'],
                ['Premium Plan', 'Premium Plan', 'الخطة المميزة', '3 months', 'Premium membership with personal training sessions', 'Best value for money', '150.00', 'monthly', '1', '2', '10'],
                ['Annual Plan', 'Annual Plan', 'الخطة السنوية', '12 months', 'Annual membership with maximum benefits', 'Long-term commitment', '500.00', 'yearly', '1', '3', '20'],
            ],
            'MembershipFeatures' => [
                [
                    'membership_name (Required, must exist in Memberships sheet)', 
                    'feature_name (Required, will be created if not exists)', 
                    'feature_name_en (Required)', 
                    'feature_name_ar (Required)', 
                    'feature_description (Required)', 
                    'feature_description_en (Required)', 
                    'feature_description_ar (Required)'
                ],
                ['Basic Plan', 'Gym Access', 'Gym Access', 'الوصول للصالة', 'Access to all gym facilities', 'Access to all gym facilities', 'الوصول لجميع مرافق الصالة'],
                ['Basic Plan', 'Locker Access', 'Locker Access', 'الوصول للخزائن', 'Access to personal lockers', 'Access to personal lockers', 'الوصول للخزائن الشخصية'],
                ['Premium Plan', 'Gym Access', 'Gym Access', 'الوصول للصالة', 'Access to all gym facilities', 'Access to all gym facilities', 'الوصول لجميع مرافق الصالة'],
                ['Premium Plan', 'Personal Training', 'Personal Training', 'تدريب شخصي', 'Personal training sessions included', 'Personal training sessions included', 'جلسات تدريب شخصي مشمولة'],
                ['Annual Plan', 'All Features', 'All Features', 'جميع المميزات', 'Access to all premium features', 'Access to all premium features', 'الوصول لجميع المميزات المميزة'],
            ],
            'Classes' => [
                [
                    'name (Required)', 
                    'name_en (Required)', 
                    'type (Required: Cardio, Strength, Power, Zumba, Yoga, Pilates, fitness, general)', 
                    'description (Required)', 
                    'status (Required: active or inactive)', 
                    'trainer_emails (Required, comma-separated for multiple trainers, e.g., "trainer1@email.com,trainer2@email.com")'
                ],
                ['Yoga Class', 'Yoga Class', 'Yoga', 'Relaxing yoga session for all levels', 'active', 'jane.smith@example.com'],
                ['Cardio Training', 'Cardio Training', 'Cardio', 'High-intensity cardio workout', 'active', 'jane.smith@example.com,mike.johnson@example.com'],
                ['Strength Training', 'Strength Training', 'Strength', 'Weight training and muscle building', 'active', 'jane.smith@example.com'],
            ],
            'ClassSchedules' => [
                [
                    'class_name (Required, must exist in Classes sheet)', 
                    'day (Required: sunday, monday, tuesday, wednesday, thursday, friday, saturday)', 
                    'start_time (Required, format: HH:MM)', 
                    'end_time (Required, format: HH:MM)'
                ],
                ['Yoga Class', 'monday', '09:00', '10:00'],
                ['Yoga Class', 'wednesday', '09:00', '10:00'],
                ['Yoga Class', 'friday', '09:00', '10:00'],
                ['Cardio Training', 'tuesday', '18:00', '19:00'],
                ['Cardio Training', 'thursday', '18:00', '19:00'],
                ['Strength Training', 'monday', '19:00', '20:00'],
                ['Strength Training', 'wednesday', '19:00', '20:00'],
                ['Strength Training', 'friday', '19:00', '20:00'],
            ],
            'ClassPricing' => [
                [
                    'class_name (Required, must exist in Classes sheet)', 
                    'price (Required)', 
                    'duration (Required: per session, monthly, weekly, etc.)'
                ],
                ['Yoga Class', '25.00', 'per session'],
                ['Cardio Training', '30.00', 'per session'],
                ['Strength Training', '35.00', 'per session'],
                ['Yoga Class', '200.00', 'monthly'],
                ['Cardio Training', '250.00', 'monthly'],
                ['Strength Training', '300.00', 'monthly'],
            ],
            'Services' => [
                [
                    'name (Required)', 
                    'name_en (Required)', 
                    'name_ar (Required)', 
                    'description (Required)', 
                    'duration (Required, in minutes)', 
                    'price (Required)', 
                    'requires_payment (Required: 1=yes, 0=no)', 
                    'booking_type (Required: unbookable, free_booking, paid_booking)', 
                    'is_available (Required: 1=yes, 0=no)', 
                    'branch_assignment (Required, comma-separated branch names, e.g., "Main Branch,Women Branch")'
                ],
                ['Personal Training', 'Personal Training', 'تدريب شخصي', 'One-on-one personal training session', '60', '100.00', '1', 'paid_booking', '1', 'Main Branch,Women Branch'],
                ['Nutrition Consultation', 'Nutrition Consultation', 'استشارة تغذية', 'Professional nutrition advice and meal planning', '45', '75.00', '1', 'paid_booking', '1', 'Main Branch'],
                ['Fitness Assessment', 'Fitness Assessment', 'تقييم اللياقة', 'Comprehensive fitness evaluation and goal setting', '30', '50.00', '1', 'paid_booking', '1', 'Main Branch,Women Branch'],
                ['Free Consultation', 'Free Consultation', 'استشارة مجانية', 'Free initial consultation', '30', '0.00', '0', 'free_booking', '1', 'Main Branch'],
            ],
            'Subscriptions' => [
                [
                    'user_email (Required, must exist in Users sheet)', 
                    'membership_name (Required, must exist in Memberships sheet)', 
                    'branch_name (Required, must exist in Branches sheet)', 
                    'start_date (Required, format: YYYY-MM-DD)', 
                    'end_date (Required, format: YYYY-MM-DD)', 
                    'invitations_used (Required, number of invitations already used)', 
                    'status (Required: active, expired, cancelled, pending)'
                ],
                ['john.doe@example.com', 'Basic Plan', 'Main Branch', '2024-01-01', '2024-02-01', '2', 'active'],
                ['jane.smith@example.com', 'Premium Plan', 'Women Branch', '2024-01-15', '2024-04-15', '5', 'active'],
                ['mike.johnson@example.com', 'Annual Plan', 'Main Branch', '2024-01-01', '2024-12-31', '10', 'active'],
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
