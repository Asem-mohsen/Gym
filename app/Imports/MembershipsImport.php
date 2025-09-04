<?php

namespace App\Imports;

use App\Models\Membership;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MembershipsImport implements ToModel, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedMemberships = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function model(array $row)
    {
        try {
            // Skip if this is a header row or empty row
            if (empty($row['name']) || $row['name'] === 'name') {
                return null;
            }
            
            // Validate that this is actually membership data (should have price field)
            if (empty($row['price'])) {
                Log::warning('Skipping non-membership data row:', $row);
                return null;
            }
            
            // Create the membership
            $membership = Membership::create([
                'site_setting_id' => $this->siteSettingId,
                'name' => [
                    'en' => $row['name_en'] ?? $row['name'] ?? '',
                    'ar' => $row['name_ar'] ?? $row['name'] ?? ''
                ],
                'period' => $row['period'] ?? '1 month',
                'general_description' => [
                    'en' => $row['description_en'] ?? $row['description'] ?? '',
                    'ar' => $row['description_ar'] ?? $row['description'] ?? ''
                ],
                'subtitle' => [
                    'en' => $row['subtitle_en'] ?? $row['subtitle'] ?? '',
                    'ar' => $row['subtitle_ar'] ?? $row['subtitle'] ?? ''
                ],
                'price' => floatval($row['price'] ?? 0),
                'billing_interval' => $row['billing_interval'] ?? 'monthly',
                'status' => $row['status'] ?? 1,
                'order' => $row['order'] ?? 0,
            ]);

            // Store imported membership data for reporting
            $this->importedMemberships[] = [
                'membership' => $membership
            ];

            Log::info('Successfully imported membership: ' . $membership->name);
            return $membership;

        } catch (\Exception $e) {
            Log::error('Membership import error: ' . $e->getMessage(), [
                'row' => $row,
                'site_setting_id' => $this->siteSettingId
            ]);
            
            // Don't add duplicate key errors to the errors array since they're expected
            if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                $this->errors[] = $e->getMessage();
            }
            
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'period' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_interval' => 'nullable|in:monthly,yearly',
            'status' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Membership name is required.',
            'price.required' => 'Membership price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',
            'billing_interval.in' => 'Billing interval must be monthly or yearly.',
        ];
    }

    public function onError(\Throwable $e)
    {
        Log::error('Membership import error: ' . $e->getMessage());
        
        // Store the error for reporting
        $this->errors[] = $e->getMessage();
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function getImportedMemberships(): array
    {
        return $this->importedMemberships;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
