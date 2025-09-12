<?php

namespace App\Imports;

use App\Models\{Membership, Feature};
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MembershipFeaturesImport implements ToCollection, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedMembershipFeatures = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $this->processMembershipFeature($row);
            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                Log::error('Membership feature import error: ' . $e->getMessage(), [
                    'row' => $row->toArray(),
                    'site_setting_id' => $this->siteSettingId
                ]);
            }
        }
    }

    protected function processMembershipFeature($row)
    {
        // Get the actual column names from the row keys
        $membershipNameKey = $this->findColumnKey($row, 'membership_name');
        $featureNameKey = $this->findColumnKey($row, 'feature_name');
        $featureNameEnKey = $this->findColumnKey($row, 'feature_name_en');
        $featureNameArKey = $this->findColumnKey($row, 'feature_name_ar');
        $featureDescEnKey = $this->findColumnKey($row, 'feature_description_en');
        $featureDescArKey = $this->findColumnKey($row, 'feature_description_ar');

        if (!$membershipNameKey || !$featureNameKey) {
            throw new \Exception("Required columns not found in row");
        }

        // Find membership by name
        $membership = Membership::where('site_setting_id', $this->siteSettingId)
            ->whereJsonContains('name->en', $row[$membershipNameKey])
            ->first();

        if (!$membership) {
            $membership = Membership::where('site_setting_id', $this->siteSettingId)
                ->where('name', 'like', '%' . $row[$membershipNameKey] . '%')
                ->first();
        }

        if (!$membership) {
            Log::warning("Membership '{$row[$membershipNameKey]}' not found, skipping feature assignment");
            return; // Skip this row instead of throwing an error
        }

        // Find or create feature
        $feature = Feature::where('site_setting_id', $this->siteSettingId)
            ->whereJsonContains('name->en', $row[$featureNameKey])
            ->first();

        if (!$feature) {
            $feature = Feature::create([
                'site_setting_id' => $this->siteSettingId,
                'name' => [
                    'en' => $row[$featureNameEnKey] ?? $row[$featureNameKey],
                    'ar' => $row[$featureNameArKey] ?? $row[$featureNameKey]
                ],
                'description' => [
                    'en' => $row[$featureDescEnKey] ?? '',
                    'ar' => $row[$featureDescArKey] ?? ''
                ],
                'status' => true
            ]);
        }

        // Check if relationship already exists and attach feature
        if (!$membership->features()->where('feature_id', $feature->id)->exists()) {
            $membership->features()->attach($feature->id);
        }

        // Store imported membership feature data for reporting
        $this->importedMembershipFeatures[] = [
            'membership' => $membership->name,
            'feature' => $feature->name,
            'created' => !$membership->features()->where('feature_id', $feature->id)->exists()
        ];

        Log::info('Membership feature import completed successfully: ' . $row[$membershipNameKey] . ' - ' . $row[$featureNameKey]);
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

    public function getImportedMembershipFeatures(): array
    {
        return $this->importedMembershipFeatures;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
