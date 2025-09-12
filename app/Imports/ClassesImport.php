<?php

namespace App\Imports;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ClassesImport implements ToModel, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedClasses = [];
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
            $typeKey = $this->findColumnKey($row, 'type') ?? 'type';
            $descriptionKey = $this->findColumnKey($row, 'description') ?? 'description';
            $statusKey = $this->findColumnKey($row, 'status') ?? 'status';
            $trainerEmailsKey = $this->findColumnKey($row, 'trainer_emails') ?? 'trainer_emails';
            
            // Skip if this is a header row or empty row
            if (empty($row[$nameKey]) || $row[$nameKey] === 'name') {
                return null;
            }
            
            // Validate that this is actually class data (should have type field)
            if (empty($row[$typeKey]) && empty($row[$descriptionKey])) {
                Log::warning('Skipping non-class data row:', $row);
                return null;
            }
            
            // Generate slug from name
            $name = $row[$nameEnKey] ?? $row[$nameKey] ?? '';
            
            // Check if class already exists to prevent duplicates
            if (ClassModel::where('site_setting_id', $this->siteSettingId)
                ->where('name', $name)
                ->exists()) {
                Log::info('Class already exists, skipping: ' . $name);
                return null;
            }
            
            $slug = Str::slug($name);
            
            // Ensure slug is unique
            $counter = 1;
            $originalSlug = $slug;
            while (ClassModel::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Class creation and insertion (explicitly exclude ID to prevent auto-increment issues)
            $classData = [
                'site_setting_id' => $this->siteSettingId,
                'name' => $name,
                'slug' => $slug,
                'type' => $row[$typeKey] ?? 'general',
                'description' => $row[$descriptionKey] ?? '',
                'status' => in_array($row[$statusKey], ['active', 'inactive']) ? $row[$statusKey] : 'active',
            ];
            
            // Explicitly remove any ID field that might be present
            unset($classData['id']);
            
            $class = ClassModel::create($classData);

            // Assign trainers if specified
            $trainers = collect();
            if (!empty($row[$trainerEmailsKey])) {
                $trainerEmails = explode(',', $row[$trainerEmailsKey]);
                $trainers = User::whereIn('email', array_map('trim', $trainerEmails))->get();
                $class->trainers()->attach($trainers->pluck('id')->toArray());
            }

            // Store imported class data for reporting
            $this->importedClasses[] = [
                'class' => $class,
                'trainers' => $trainers
            ];

            Log::info('Class import completed successfully: ' . $name);
            return $class;

        } catch (\Exception $e) {
            Log::error('Class import error: ' . $e->getMessage(), [
                'row' => $row,
                'site_setting_id' => $this->siteSettingId
            ]);
            
            if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                $this->errors[] = $e->getMessage();
            }
            
            return null;
        }
    }

    public function onError(\Throwable $e)
    {
        Log::error('Class import error: ' . $e->getMessage());
        
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

    public function getImportedClasses(): array
    {
        return $this->importedClasses;
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
