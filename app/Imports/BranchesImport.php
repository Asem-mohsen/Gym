<?php

namespace App\Imports;

use Exception;
use Throwable;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BranchesImport implements ToModel, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedBranches = [];
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
            $locationKey = $this->findColumnKey($row, 'location') ?? 'location';
            $locationEnKey = $this->findColumnKey($row, 'location_en') ?? 'location_en';
            $locationArKey = $this->findColumnKey($row, 'location_ar') ?? 'location_ar';
            $typeKey = $this->findColumnKey($row, 'type') ?? 'type';
            $sizeKey = $this->findColumnKey($row, 'size') ?? 'size';
            $managerEmailKey = $this->findColumnKey($row, 'manager_email') ?? 'manager_email';
            
            // Skip if this is a header row or empty row
            if (empty($row[$nameKey]) || $row[$nameKey] === 'name') {
                return null;
            }
            
            // Validate that this is actually branch data (should have location field)
            if (empty($row[$locationKey]) && empty($row[$locationEnKey]) && empty($row[$locationArKey])) {
                Log::warning('Skipping non-branch data row:', $row);
                return null;
            }
            
            // Check if branch already exists to prevent duplicates
            if (Branch::where('name', $row[$nameKey])->where('site_setting_id', $this->siteSettingId)->exists()) {
                Log::info('Branch already exists, skipping: ' . $row[$nameKey]);
                return null;
            }
            
            // Find manager by email or name
            $manager = null;
            if (!empty($row[$managerEmailKey])) {
                $manager = User::where('email', $row[$managerEmailKey])->first();
            }

            // Branch creation and insertion (explicitly exclude ID to prevent auto-increment issues)
            $branchData = [
                'manager_id' => $manager ? $manager->id : null,
                'site_setting_id' => $this->siteSettingId,
                'name' => [
                    'en' => $row[$nameEnKey] ?? $row[$nameKey] ?? '',
                    'ar' => $row[$nameArKey] ?? $row[$nameKey] ?? ''
                ],
                'location' => [
                    'en' => $row[$locationEnKey] ?? $row[$locationKey] ?? 'Default Location',
                    'ar' => $row[$locationArKey] ?? $row[$locationKey] ?? 'الموقع الافتراضي'
                ],
                'type' => $row[$typeKey] ?? 'mix',
                'size' => $row[$sizeKey] ?? null,
                'facebook_url' => $row['facebook_url'] ?? null,
                'instagram_url' => $row['instagram_url'] ?? null,
                'x_url' => $row['x_url'] ?? null,
            ];
            
            // Explicitly remove any ID field that might be present
            unset($branchData['id']);
            
            $branch = Branch::create($branchData);

            // Store imported branch data for reporting
            $this->importedBranches[] = [
                'branch' => $branch,
                'manager' => $manager
            ];

            Log::info('Branch import completed successfully: ' . $row[$nameKey]);
            return $branch;

        } catch (Exception $e) {
            Log::error('Branch import error: ' . $e->getMessage(), [
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

    public function onError(Throwable $e)
    {
        Log::error('Branch import error: ' . $e->getMessage());
        
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

    public function getImportedBranches(): array
    {
        return $this->importedBranches;
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
