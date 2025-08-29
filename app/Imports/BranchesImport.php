<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BranchesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedBranches = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function model(array $row)
    {
        try {
            // Find manager by email or name
            $manager = null;
            if (!empty($row['manager_email'])) {
                $manager = User::where('email', $row['manager_email'])->first();
            } elseif (!empty($row['manager_name'])) {
                $manager = User::where('name', $row['manager_name'])->first();
            }

            // Create the branch
            $branch = Branch::create([
                'manager_id' => $manager ? $manager->id : null,
                'site_setting_id' => $this->siteSettingId,
                'name' => [
                    'en' => $row['name_en'] ?? $row['name'] ?? '',
                    'ar' => $row['name_ar'] ?? $row['name'] ?? ''
                ],
                'location' => [
                    'en' => $row['location_en'] ?? $row['location'] ?? '',
                    'ar' => $row['location_ar'] ?? $row['location'] ?? ''
                ],
                'type' => $row['type'] ?? 'mix',
                'size' => $row['size'] ?? null,
                'facebook_url' => $row['facebook_url'] ?? null,
                'instagram_url' => $row['instagram_url'] ?? null,
                'x_url' => $row['x_url'] ?? null,
            ]);

            // Store imported branch data for reporting
            $this->importedBranches[] = [
                'branch' => $branch,
                'manager' => $manager
            ];

            return $branch;

        } catch (\Exception $e) {
            Log::error('Branch import error: ' . $e->getMessage(), [
                'row' => $row,
                'site_setting_id' => $this->siteSettingId
            ]);
            
            $this->errors[] = [
                'row' => $row,
                'error' => $e->getMessage()
            ];
            
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'location' => 'required|string|max:500',
            'location_en' => 'nullable|string|max:500',
            'location_ar' => 'nullable|string|max:500',
            'type' => 'nullable|in:mix,women,men',
            'size' => 'nullable|integer|min:1',
            'manager_email' => 'nullable|email|exists:users,email',
            'manager_name' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'x_url' => 'nullable|url',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Branch name is required.',
            'location.required' => 'Branch location is required.',
            'type.in' => 'Branch type must be mix, women, or men.',
            'manager_email.email' => 'Manager email must be a valid email address.',
            'manager_email.exists' => 'Manager with this email does not exist.',
        ];
    }

    public function onError(\Throwable $e)
    {
        Log::error('Branch import error: ' . $e->getMessage());
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function getImportedBranches(): array
    {
        return $this->importedBranches;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
