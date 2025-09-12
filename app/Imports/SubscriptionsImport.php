<?php

namespace App\Imports;

use Exception;
use Carbon\Carbon;
use Throwable;
use App\Models\{User, Membership, Branch, Subscription};
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubscriptionsImport implements ToCollection, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedSubscriptions = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $this->processSubscription($row);
            } catch (Exception $e) {
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                Log::error('Subscription import error: ' . $e->getMessage(), [
                    'row' => $row->toArray(),
                    'site_setting_id' => $this->siteSettingId
                ]);
            }
        }
    }

    protected function processSubscription($row)
    {
        // Get the actual column names from the row keys
        $userEmailKey = $this->findColumnKey($row, 'user_email');
        $membershipNameKey = $this->findColumnKey($row, 'membership_name');
        $branchNameKey = $this->findColumnKey($row, 'branch_name');
        $startDateKey = $this->findColumnKey($row, 'start_date');
        $endDateKey = $this->findColumnKey($row, 'end_date');
        $statusKey = $this->findColumnKey($row, 'status');
        $invitationsUsedKey = $this->findColumnKey($row, 'invitations_used');

        if (!$userEmailKey || !$membershipNameKey || !$branchNameKey || !$startDateKey || !$endDateKey || !$statusKey || !$invitationsUsedKey) {
            throw new Exception("Required columns not found in row");
        }

        // Find user by email
        $user = User::where('email', $row[$userEmailKey])
            ->whereHas('gyms', function ($query) {
                $query->where('site_setting_id', $this->siteSettingId);
            })
            ->first();

        if (!$user) {
            Log::warning("User with email '{$row[$userEmailKey]}' not found, skipping subscription creation");
            return; // Skip this row instead of throwing an error
        }

        // Find membership by name
        $membership = Membership::where('site_setting_id', $this->siteSettingId)
            ->whereJsonContains('name->en', $row[$membershipNameKey])
            ->first();

        if (!$membership) {
            // Try to find by name field as well
            $membership = Membership::where('site_setting_id', $this->siteSettingId)
                ->where('name', 'like', '%' . $row[$membershipNameKey] . '%')
                ->first();
        }

        if (!$membership) {
            Log::warning("Membership '{$row[$membershipNameKey]}' not found, skipping subscription creation");
            return; // Skip this row instead of throwing an error
        }

        // Find branch by name
        $branch = Branch::where('site_setting_id', $this->siteSettingId)
            ->whereJsonContains('name->en', $row[$branchNameKey])
            ->first();

        if (!$branch) {
            // Try to find by name field as well
            $branch = Branch::where('site_setting_id', $this->siteSettingId)
                ->where('name', 'like', '%' . $row[$branchNameKey] . '%')
                ->first();
        }

        if (!$branch) {
            Log::warning("Branch '{$row[$branchNameKey]}' not found, skipping subscription creation");
            return; // Skip this row instead of throwing an error
        }

        // Validate dates
        $startDate = Carbon::createFromFormat('Y-m-d', $row[$startDateKey]);
        $endDate = Carbon::createFromFormat('Y-m-d', $row[$endDateKey]);

        if ($startDate >= $endDate) {
            throw new Exception("Start date must be before end date");
        }

        // Validate status
        $validStatuses = ['active', 'expired', 'cancelled', 'pending'];
        if (!in_array($row[$statusKey], $validStatuses)) {
            throw new Exception("Invalid status '{$row[$statusKey]}'. Must be one of: " . implode(', ', $validStatuses));
        }

        // Validate invitations_used
        if (!is_numeric($row[$invitationsUsedKey]) || $row[$invitationsUsedKey] < 0) {
            throw new Exception("Invalid invitations_used '{$row[$invitationsUsedKey]}'. Must be a non-negative number");
        }

        // Check if subscription already exists and create subscription
        $existingSubscription = Subscription::where('user_id', $user->id)
            ->where('membership_id', $membership->id)
            ->where('branch_id', $branch->id)
            ->where('start_date', $startDate->format('Y-m-d'))
            ->first();

        if (!$existingSubscription) {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'membership_id' => $membership->id,
                'branch_id' => $branch->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'invitations_used' => $row[$invitationsUsedKey],
                'status' => $row[$statusKey]
            ]);

            $this->importedSubscriptions[] = [
                'user' => $user->email,
                'membership' => $membership->name,
                'branch' => $branch->name,
                'start_date' => $subscription->start_date,
                'end_date' => $subscription->end_date,
                'status' => $subscription->status
            ];
        }

        Log::info('Subscription import completed successfully: ' . $row[$userEmailKey] . ' - ' . $row[$membershipNameKey]);
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

    public function getImportedSubscriptions(): array
    {
        return $this->importedSubscriptions;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
