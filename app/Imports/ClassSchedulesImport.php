<?php

namespace App\Imports;

use App\Models\{ClassModel, ClassSchedule};
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClassSchedulesImport implements ToCollection, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedClassSchedules = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $this->processClassSchedule($row);
            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                Log::error('Class schedule import error: ' . $e->getMessage(), [
                    'row' => $row->toArray(),
                    'site_setting_id' => $this->siteSettingId
                ]);
            }
        }
    }

    protected function processClassSchedule($row)
    {
        // Get the actual column names from the row keys
        $classNameKey = $this->findColumnKey($row, 'class_name');
        $dayKey = $this->findColumnKey($row, 'day');
        $startTimeKey = $this->findColumnKey($row, 'start_time');
        $endTimeKey = $this->findColumnKey($row, 'end_time');

        if (!$classNameKey || !$dayKey || !$startTimeKey || !$endTimeKey) {
            throw new \Exception("Required columns not found in row");
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
            Log::warning("Class '{$row[$classNameKey]}' not found, skipping schedule creation");
            return; // Skip this row instead of throwing an error
        }

        // Validate day
        $validDays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        if (!in_array(strtolower($row[$dayKey]), $validDays)) {
            throw new \Exception("Invalid day '{$row[$dayKey]}'. Must be one of: " . implode(', ', $validDays));
        }

        // Validate time format
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $row[$startTimeKey])) {
            throw new \Exception("Invalid start_time format '{$row[$startTimeKey]}'. Use HH:MM format");
        }

        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $row[$endTimeKey])) {
            throw new \Exception("Invalid end_time format '{$row[$endTimeKey]}'. Use HH:MM format");
        }

        // Check if schedule already exists and create schedule
        $existingSchedule = ClassSchedule::where('class_id', $classModel->id)
            ->where('day', strtolower($row[$dayKey]))
            ->where('start_time', $row[$startTimeKey])
            ->where('end_time', $row[$endTimeKey])
            ->first();

        if (!$existingSchedule) {
            $schedule = ClassSchedule::create([
                'class_id' => $classModel->id,
                'day' => strtolower($row[$dayKey]),
                'start_time' => $row[$startTimeKey],
                'end_time' => $row[$endTimeKey]
            ]);

            $this->importedClassSchedules[] = [
                'class' => $classModel->name,
                'day' => $schedule->day,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time
            ];
        }

        Log::info('Class schedule import completed successfully: ' . $row[$classNameKey] . ' - ' . $row[$dayKey]);
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

    public function onError(\Throwable $e)
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

    public function getImportedClassSchedules(): array
    {
        return $this->importedClassSchedules;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
