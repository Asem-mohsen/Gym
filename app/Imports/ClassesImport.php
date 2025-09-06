<?php

namespace App\Imports;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
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

    public function model(array $row)
    {
        try {
            // Skip if this is a header row or empty row
            if (empty($row['name']) || $row['name'] === 'name') {
                return null;
            }
            
            // Validate that this is actually class data (should have type field)
            if (empty($row['type']) && empty($row['description'])) {
                Log::warning('Skipping non-class data row:', $row);
                return null;
            }
            
            // Generate slug from name
            $name = $row['name_en'] ?? $row['name'] ?? '';
            
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

            // Create the class
            $class = ClassModel::create([
                'site_setting_id' => $this->siteSettingId,
                'name' => $name,
                'slug' => $slug,
                'type' => $row['type'] ?? 'general',
                'description' => $row['description'] ?? '',
                'status' => in_array($row['status'], ['active', 'inactive']) ? $row['status'] : 'active',
            ]);

            // Assign trainers if specified
            if (!empty($row['trainer_emails'])) {
                $trainerEmails = explode(',', $row['trainer_emails']);
                $trainers = User::whereIn('email', array_map('trim', $trainerEmails))->get();
                $class->trainers()->attach($trainers->pluck('id')->toArray());
            }

            // Store imported class data for reporting
            $this->importedClasses[] = [
                'class' => $class,
                'trainers' => $trainers ?? collect()
            ];

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

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'trainer_emails' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Class name is required.',
            'status.in' => 'Status must be active or inactive.',
        ];
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
}
