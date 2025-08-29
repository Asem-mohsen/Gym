<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\GymDataImportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GymDataExport;

class GymDataImportController extends Controller
{
    protected $importService;

    public function __construct()
    {
        // Middleware is applied in routes file
    }

    /**
     * Show the import form
     */
    public function index()
    {
        $siteSettingId = session('site_setting_id');
        $template = GymDataImportService::getImportTemplate();
        
        return view('admin.import.index', compact('template'));
    }

    /**
     * Handle the import process
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'import_file' => 'required|file|max:10240', // 10MB max
            ]);

            $file = $request->file('import_file');
            $siteSettingId = session('site_setting_id');

            // Validate file
            $validationErrors = GymDataImportService::validateImportFile($file);
            if (!empty($validationErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File validation failed',
                    'errors' => $validationErrors
                ], 422);
            }

            // Perform import
            $importService = new GymDataImportService($siteSettingId);
            $results = $importService->importGymData($file);

            // Log the import
            Log::info('Gym data import completed', [
                'user_id' => Auth::id(),
                'site_setting_id' => $siteSettingId,
                'file_name' => $file->getClientOriginalName(),
                'summary' => $results['summary']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Import completed successfully',
                'data' => $results
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
    public function downloadTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        try {
            $siteSettingId = session('site_setting_id');
            $siteSetting = \App\Models\SiteSetting::find($siteSettingId);

            if (!$siteSetting) {
                abort(404, 'Site setting not found');
            }

            // Create a simple template export instead of using GymDataExport
            $templateData = [
                'Users' => [
                    ['name', 'email', 'phone', 'address', 'gender', 'role', 'status', 'password'],
                    ['John Doe', 'john@example.com', '1234567890', '123 Main St', 'male', 'regular_user', '1', ''],
                    ['Jane Smith', 'jane@example.com', '0987654321', '456 Oak Ave', 'female', 'trainer', '1', ''],
                ],
                'Branches' => [
                    ['name', 'name_en', 'name_ar', 'location', 'location_en', 'location_ar', 'type', 'size', 'manager_email'],
                    ['Main Branch', 'Main Branch', 'الفرع الرئيسي', 'Downtown', 'Downtown', 'وسط المدينة', 'mix', '1000', 'manager@example.com'],
                ],
                'Memberships' => [
                    ['name', 'name_en', 'name_ar', 'period', 'description', 'price', 'billing_interval', 'status', 'order'],
                    ['Basic Plan', 'Basic Plan', 'الخطة الأساسية', '1 month', 'Basic membership', '50.00', 'monthly', '1', '1'],
                ],
                'Classes' => [
                    ['name', 'name_en', 'type', 'description', 'status', 'trainer_emails'],
                    ['Yoga Class', 'Yoga Class', 'fitness', 'Relaxing yoga session', 'active', 'trainer@example.com'],
                ],
                'Services' => [
                    ['name', 'name_en', 'name_ar', 'description', 'duration', 'price', 'requires_payment', 'booking_type', 'is_available'],
                    ['Personal Training', 'Personal Training', 'تدريب شخصي', 'One-on-one training', '60', '100.00', 'true', 'paid_booking', 'true'],
                ]
            ];

            $export = new \App\Exports\TemplateExport($templateData);
            
            $fileName = 'gym_import_template_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return Excel::download($export, $fileName);

        } catch (\Exception $e) {
            Log::error('Template download failed: ' . $e->getMessage());
            abort(500, 'Failed to generate template');
        }
    }

    /**
     * Get import status and results
     */
    public function getImportStatus(Request $request): JsonResponse
    {
        // This could be used for async import status checking
        // For now, we'll return a simple response
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
        // This could show previous import results
        // For now, we'll return a simple view
        return view('admin.import.history');
    }
}
