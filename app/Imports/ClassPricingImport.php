<?php

namespace App\Imports;

use Exception;
use Throwable;
use App\Models\{ClassModel, ClassPricing};
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClassPricingImport implements ToCollection, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedClassPricing = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $this->processClassPricing($row);
            } catch (Exception $e) {
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                Log::error('Class pricing import error: ' . $e->getMessage(), [
                    'row' => $row->toArray(),
                    'site_setting_id' => $this->siteSettingId
                ]);
            }
        }
    }

    protected function processClassPricing($row)
    {
        // Get the actual column names from the row keys
        $classNameKey = $this->findColumnKey($row, 'class_name');
        $priceKey = $this->findColumnKey($row, 'price');
        $durationKey = $this->findColumnKey($row, 'duration');

        if (!$classNameKey || !$priceKey || !$durationKey) {
            throw new Exception("Required columns not found in row");
        }

        // Find class by name
        $classModel = ClassModel::where('site_setting_id', $this->siteSettingId)
            ->whereJsonContains('name->en', $row[$classNameKey])
            ->first();

        if (!$classModel) {
            $classModel = ClassModel::where('site_setting_id', $this->siteSettingId)
                ->where('name', 'like', '%' . $row[$classNameKey] . '%')
                ->first();
        }

        if (!$classModel) {
            Log::warning("Class '{$row[$classNameKey]}' not found, skipping pricing creation");
            return; // Skip this row instead of throwing an error
        }

        // Validate price
        if (!is_numeric($row[$priceKey]) || $row[$priceKey] < 0) {
            throw new Exception("Invalid price '{$row[$priceKey]}'. Must be a positive number");
        }

        // Check if pricing already exists and create pricing
        $existingPricing = ClassPricing::where('class_id', $classModel->id)
            ->where('duration', $row[$durationKey])
            ->first();

        if (!$existingPricing) {
            $pricing = ClassPricing::create([
                'class_id' => $classModel->id,
                'price' => $row[$priceKey],
                'duration' => $row[$durationKey]
            ]);

            $this->importedClassPricing[] = [
                'class' => $classModel->name,
                'price' => $pricing->price,
                'duration' => $pricing->duration
            ];
        }

        Log::info('Class pricing import completed successfully: ' . $row[$classNameKey] . ' - ' . $row[$durationKey]);
    }

    protected function findColumnKey($row, $searchTerm)
    {
        // Convert to array if it's a Collection
        if (is_object($row) && method_exists($row, 'toArray')) {
            $row = $row->toArray();
        } elseif (is_object($row)) {
            $row = (array) $row;
        }
        
        foreach (array_keys($row) as $key) {
            if (strpos($key, $searchTerm) !== false) {
                return $key;
            }
        }
        return null;
    }

    public function onError(Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getImportedClassPricing(): array
    {
        return $this->importedClassPricing;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
