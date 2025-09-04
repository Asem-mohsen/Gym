<?php

namespace App\Services;

use App\Imports\UsersImport;
use App\Imports\BranchesImport;
use App\Imports\MembershipsImport;
use App\Imports\ClassesImport;
use App\Imports\GymDataImport;
use App\Imports\ServicesImport;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class GymDataImportService
{
    protected $siteSettingId;
    protected $importResults = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    /**
     * Import all gym data from Excel file
     */
    public function importGymData(UploadedFile $file): array
    {
        try {
            DB::beginTransaction();

            $this->importResults = [
                'users' => [],
                'branches' => [],
                'memberships' => [],
                'classes' => [],
                'services' => [],
                'errors' => [],
                'summary' => []
            ];

            $filePath = $file->store('imports', 'local');
            $fullPath = Storage::disk('local')->path($filePath);

            $import = new GymDataImport($this->siteSettingId);
            Excel::import($import, $fullPath, null, \Maatwebsite\Excel\Excel::XLSX);
            
            $this->importResults = $import->getImportResults();

            $this->generateSummary();

            Storage::disk('local')->delete($filePath);

            DB::commit();

            return $this->importResults;

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Gym data import failed: ' . $e->getMessage(), [
                'site_setting_id' => $this->siteSettingId,
                'file' => $file->getClientOriginalName()
            ]);

            // Clean up the uploaded file if it exists
            if (isset($filePath) && Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }

            throw $e;
        }
    }



    /**
     * Generate import summary
     */
    protected function generateSummary(): void
    {
        $totalImported = 0;
        $totalErrors = 0;

        foreach (['users', 'branches', 'memberships', 'classes', 'services'] as $type) {
            $totalImported += $this->importResults[$type]['count'] ?? 0;
            $totalErrors += count($this->importResults[$type]['errors'] ?? []);
        }

        $totalErrors += count($this->importResults['errors'] ?? []);

        $this->importResults['summary'] = [
            'total_imported' => $totalImported,
            'total_errors' => $totalErrors,
            'success_rate' => $totalImported > 0 ? round(($totalImported / ($totalImported + $totalErrors)) * 100, 2) : 0,
            'imported_at' => now()->toDateTimeString(),
            'site_setting_id' => $this->siteSettingId
        ];
    }

    /**
     * Get import template structure
     */
    public static function getImportTemplate(): array
    {
        return [
            'Users' => [
                'name' => 'Required - User full name',
                'email' => 'Required - Unique email address',
                'phone' => 'Optional - Phone number',
                'address' => 'Optional - User address',
                'gender' => 'Optional - male or female',
                'role' => 'Optional - regular_user, admin, trainer, staff',
                'status' => 'Optional - 1 for active, 0 for inactive',
                'password' => 'Optional - If not provided, random password will be generated'
            ],
            'Branches' => [
                'name' => 'Required - Branch name (or use name_en/name_ar for multilingual)',
                'name_en' => 'Optional - English branch name',
                'name_ar' => 'Optional - Arabic branch name',
                'location' => 'Required - Branch location (or use location_en/location_ar for multilingual)',
                'location_en' => 'Optional - English location',
                'location_ar' => 'Optional - Arabic location',
                'type' => 'Optional - mix, women, or men (default: mix)',
                'size' => 'Optional - Branch size (number)',
                'manager_email' => 'Optional - Manager email (must exist in users)',
                'manager_name' => 'Optional - Manager name (must exist in users)',
                'facebook_url' => 'Optional - Facebook URL',
                'instagram_url' => 'Optional - Instagram URL',
                'x_url' => 'Optional - X (Twitter) URL'
            ],
            'Memberships' => [
                'name' => 'Required - Membership name (or use name_en/name_ar for multilingual)',
                'name_en' => 'Optional - English membership name',
                'name_ar' => 'Optional - Arabic membership name',
                'period' => 'Optional - Period description (default: 1 month)',
                'description' => 'Optional - Description (or use description_en/description_ar for multilingual)',
                'description_en' => 'Optional - English description',
                'description_ar' => 'Optional - Arabic description',
                'price' => 'Required - Price (number)',
                'billing_interval' => 'Optional - monthly or yearly (default: monthly)',
                'status' => 'Optional - 1 for active, 0 for inactive (default: 1)',
                'order' => 'Optional - Display order (number, default: 0)'
            ],
            'Classes' => [
                'name' => 'Required - Class name (or use name_en for English)',
                'name_en' => 'Optional - English class name',
                'type' => 'Optional - Class type (default: general)',
                'description' => 'Optional - Description (or use description_en/description_ar for multilingual)',
                'description_en' => 'Optional - English description',
                'description_ar' => 'Optional - Arabic description',
                'status' => 'Optional - active or inactive (default: active)',
                'trainer_emails' => 'Optional - Comma-separated trainer emails'
            ],
            'Services' => [
                'name' => 'Required - Service name (or use name_en/name_ar for multilingual)',
                'name_en' => 'Optional - English service name',
                'name_ar' => 'Optional - Arabic service name',
                'description' => 'Optional - Description (or use description_en/description_ar for multilingual)',
                'description_en' => 'Optional - English description',
                'description_ar' => 'Optional - Arabic description',
                'duration' => 'Optional - Duration in minutes (default: 60)',
                'price' => 'Required - Price (number)',
                'requires_payment' => 'Optional - true or false (default: false)',
                'booking_type' => 'Optional - free_booking, paid_booking, or no_booking (default: free_booking)',
                'is_available' => 'Optional - true or false (default: true)',
                'sort_order' => 'Optional - Display order (number, default: 0)',
                'branch_names' => 'Optional - Comma-separated branch names to assign service to'
            ]
        ];
    }

    /**
     * Validate import file
     */
    public static function validateImportFile(UploadedFile $file): array
    {
        $errors = [];

        // Check file type
        $allowedTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.ms-excel', // .xls
        ];

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            $errors[] = 'File must be an Excel file (.xlsx or .xls)';
        }

        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            $errors[] = 'File size must be less than 10MB';
        }

        return $errors;
    }
}
