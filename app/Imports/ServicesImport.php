<?php

namespace App\Imports;

use App\Models\Service;
use App\Models\Branch;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ServicesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedServices = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function model(array $row)
    {
        try {
            // Create the service
            $service = Service::create([
                'site_setting_id' => $this->siteSettingId,
                'name' => [
                    'en' => $row['name_en'] ?? $row['name'] ?? '',
                    'ar' => $row['name_ar'] ?? $row['name'] ?? ''
                ],
                'description' => [
                    'en' => $row['description_en'] ?? $row['description'] ?? '',
                    'ar' => $row['description_ar'] ?? $row['description'] ?? ''
                ],
                'duration' => $row['duration'] ?? 60, // Default 60 minutes
                'price' => $row['price'] ?? 0,
                'requires_payment' => $row['requires_payment'] ?? false,
                'booking_type' => $row['booking_type'] ?? 'free_booking',
                'is_available' => $row['is_available'] ?? true,
                'sort_order' => $row['sort_order'] ?? 0,
            ]);

            // Assign branches if specified
            if (!empty($row['branch_names'])) {
                $branchNames = explode(',', $row['branch_names']);
                $branches = Branch::where('site_setting_id', $this->siteSettingId)
                    ->whereIn('name->en', array_map('trim', $branchNames))
                    ->orWhereIn('name->ar', array_map('trim', $branchNames))
                    ->get();
                
                if ($branches->isNotEmpty()) {
                    $service->branches()->attach($branches->pluck('id')->toArray(), ['is_available' => true]);
                }
            }

            // Store imported service data for reporting
            $this->importedServices[] = [
                'service' => $service,
                'branches' => $branches ?? collect()
            ];

            return $service;

        } catch (\Exception $e) {
            Log::error('Service import error: ' . $e->getMessage(), [
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
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'requires_payment' => 'nullable|boolean',
            'booking_type' => 'nullable|in:free_booking,paid_booking,no_booking',
            'is_available' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'branch_names' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Service name is required.',
            'price.required' => 'Service price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',
            'duration.integer' => 'Duration must be a number.',
            'duration.min' => 'Duration must be at least 1 minute.',
            'booking_type.in' => 'Booking type must be free_booking, paid_booking, or no_booking.',
        ];
    }

    public function onError(\Throwable $e)
    {
        Log::error('Service import error: ' . $e->getMessage());
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function getImportedServices(): array
    {
        return $this->importedServices;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
