<?php

namespace App\Imports;

use App\Models\Service;
use App\Models\Branch;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ServicesImport implements ToModel, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedServices = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function model($row)
    {
        try {
            // Convert row to array if it's a collection or object
            if (is_object($row)) {
                if (method_exists($row, 'toArray')) {
                    $row = $row->toArray();
                } else {
                    $row = (array) $row;
                }
            }
            
            // Ensure row is an array
            if (!is_array($row)) {
                Log::warning('Row is not an array:', ['row' => $row, 'type' => gettype($row)]);
                return null;
            }
            
            // Find the actual column keys (they have descriptions in parentheses)
            $nameKey = $this->findColumnKey($row, 'name') ?? 'name';
            $nameEnKey = $this->findColumnKey($row, 'name_en') ?? 'name_en';
            $nameArKey = $this->findColumnKey($row, 'name_ar') ?? 'name_ar';
            $descriptionKey = $this->findColumnKey($row, 'description') ?? 'description';
            $durationKey = $this->findColumnKey($row, 'duration') ?? 'duration';
            $priceKey = $this->findColumnKey($row, 'price') ?? 'price';
            $requiresPaymentKey = $this->findColumnKey($row, 'requires_payment') ?? 'requires_payment';
            $bookingTypeKey = $this->findColumnKey($row, 'booking_type') ?? 'booking_type';
            $isAvailableKey = $this->findColumnKey($row, 'is_available') ?? 'is_available';
            $branchAssignmentKey = $this->findColumnKey($row, 'branch_assignment') ?? 'branch_assignment';
            
            if (empty($row[$nameKey]) || $row[$nameKey] === 'name') {
                return null;
            }
            
            if (empty($row[$durationKey]) && empty($row[$priceKey])) {
                return null;
            }
            
            // Check if service already exists to prevent duplicates
            $serviceName = $row[$nameEnKey] ?? $row[$nameKey] ?? '';
            if (Service::where('site_setting_id', $this->siteSettingId)
                ->whereJsonContains('name->en', $serviceName)
                ->exists()) {
                Log::info('Service already exists, skipping: ' . $serviceName);
                return null;
            }

            // Service creation and insertion (explicitly exclude ID to prevent auto-increment issues)
            $serviceData = [
                'site_setting_id' => $this->siteSettingId,
                'name' => [
                    'en' => $serviceName,
                    'ar' => $row[$nameArKey] ?? $row[$nameKey] ?? ''
                ],
                'description' => [
                    'en' => $row[$descriptionKey] ?? '',
                    'ar' => $row[$descriptionKey] ?? ''
                ],
                'duration' => $row[$durationKey] ?? 60,
                'price' => floatval($row[$priceKey] ?? 0),
                'requires_payment' => filter_var($row[$requiresPaymentKey] ?? '1', FILTER_VALIDATE_BOOLEAN),
                'booking_type' => $row[$bookingTypeKey] ?? 'paid_booking',
                'is_available' => filter_var($row[$isAvailableKey] ?? '1', FILTER_VALIDATE_BOOLEAN),
                'sort_order' => $row['sort_order'] ?? 0,
            ];
            
            // Explicitly remove any ID field that might be present
            unset($serviceData['id']);
            
            $service = Service::create($serviceData);

            // Assign branches if specified
            $branches = collect();
            $branchNames = $row[$branchAssignmentKey] ?? '';
            if (!empty($branchNames)) {
                $branchNames = explode(',', $branchNames);
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
                'branches' => $branches
            ];

            Log::info('Service import completed successfully: ' . $serviceName);
            return $service;

        } catch (\Exception $e) {
            Log::error('Service import error: ' . $e->getMessage(), [
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

    public function onError(\Throwable $e)
    {
        Log::error('Service import error: ' . $e->getMessage());
        
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

    public function getImportedServices(): array
    {
        return $this->importedServices;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Find column key by searching for a term within the key names
     */
    protected function findColumnKey($row, $searchTerm)
    {
        // Convert row to array if it's a Collection
        if (is_object($row) && method_exists($row, 'toArray')) {
            $row = $row->toArray();
        } elseif (is_object($row)) {
            $row = (array) $row;
        }
        
        $keys = array_keys($row);
        
        foreach ($keys as $key) {
            if (stripos($key, $searchTerm) !== false) {
                return $key;
            }
        }
        
        return null;
    }
}
