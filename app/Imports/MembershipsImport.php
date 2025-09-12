<?php

namespace App\Imports;

use Exception;
use Throwable;
use App\Models\Membership;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
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
            $periodKey = $this->findColumnKey($row, 'period') ?? 'period';
            $descriptionKey = $this->findColumnKey($row, 'description') ?? 'description';
            $subtitleKey = $this->findColumnKey($row, 'subtitle') ?? 'subtitle';
            $priceKey = $this->findColumnKey($row, 'price') ?? 'price';
            $billingIntervalKey = $this->findColumnKey($row, 'billing_interval') ?? 'billing_interval';
            $statusKey = $this->findColumnKey($row, 'status') ?? 'status';
            $orderKey = $this->findColumnKey($row, 'order') ?? 'order';
            $invitationLimitKey = $this->findColumnKey($row, 'invitation_limit') ?? 'invitation_limit';
            
            // Skip if this is a header row or empty row
            if (empty($row[$nameKey]) || $row[$nameKey] === 'name') {
                return null;
            }
            
            // Validate that this is actually membership data (should have price field)
            if (empty($row[$priceKey])) {
                Log::warning('Skipping non-membership data row:', $row);
                return null;
            }
            
            // Membership creation and insertion (explicitly exclude ID to prevent auto-increment issues)
            $membershipData = [
                'site_setting_id' => $this->siteSettingId,
                'name' => [
                    'en' => $row[$nameEnKey] ?? $row[$nameKey] ?? '',
                    'ar' => $row[$nameArKey] ?? $row[$nameKey] ?? ''
                ],
                'period' => $row[$periodKey] ?? '1 month',
                'general_description' => [
                    'en' => $row[$descriptionKey] ?? '',
                    'ar' => $row[$descriptionKey] ?? ''
                ],
                'subtitle' => [
                    'en' => $row[$subtitleKey] ?? '',
                    'ar' => $row[$subtitleKey] ?? ''
                ],
                'price' => floatval($row[$priceKey] ?? 0),
                'billing_interval' => $row[$billingIntervalKey] ?? 'monthly',
                'status' => $row[$statusKey] ?? 1,
                'order' => $row[$orderKey] ?? 0,
                'invitation_limit' => intval($row[$invitationLimitKey] ?? 0),
            ];
            
            // Explicitly remove any ID field that might be present
            unset($membershipData['id']);
            
            $membership = Membership::create($membershipData);

            // Store imported membership data for reporting
            $this->importedMemberships[] = [
                'membership' => $membership
            ];

            Log::info('Membership import completed successfully: ' . $row[$nameKey]);
            return $membership;

        } catch (Exception $e) {
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

    public function onError(Throwable $e)
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
