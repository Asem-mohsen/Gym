<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class GymDataImport implements WithMultipleSheets
{
    protected $siteSettingId;
    protected $importResults = [];
    protected $sheetInstances = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function sheets(): array
    {
        Log::info('Creating sheet instances for site_setting_id: ' . $this->siteSettingId);
        
        $this->sheetInstances = [
            'Users' => new UsersImport($this->siteSettingId),
            'Branches' => new BranchesImport($this->siteSettingId),
            'Memberships' => new MembershipsImport($this->siteSettingId),
            'MembershipFeatures' => new MembershipFeaturesImport($this->siteSettingId),
            'Classes' => new ClassesImport($this->siteSettingId),
            'ClassSchedules' => new ClassSchedulesImport($this->siteSettingId),
            'ClassPricing' => new ClassPricingImport($this->siteSettingId),
            'Services' => new ServicesImport($this->siteSettingId),
            'Subscriptions' => new SubscriptionsImport($this->siteSettingId),
        ];
        
        Log::info('Sheet instances created successfully');
        return $this->sheetInstances;
    }

    public function getImportResults(): array
    {
        $results = [
            'users' => [
                'imported' => $this->sheetInstances['Users']->getImportedUsers(),
                'errors' => $this->sheetInstances['Users']->getErrors(),
                'count' => count($this->sheetInstances['Users']->getImportedUsers())
            ],
            'branches' => [
                'imported' => $this->sheetInstances['Branches']->getImportedBranches(),
                'errors' => $this->sheetInstances['Branches']->getErrors(),
                'count' => count($this->sheetInstances['Branches']->getImportedBranches())
            ],
            'memberships' => [
                'imported' => $this->sheetInstances['Memberships']->getImportedMemberships(),
                'errors' => $this->sheetInstances['Memberships']->getErrors(),
                'count' => count($this->sheetInstances['Memberships']->getImportedMemberships())
            ],
            'membership_features' => [
                'imported' => $this->sheetInstances['MembershipFeatures']->getImportedMembershipFeatures(),
                'errors' => $this->sheetInstances['MembershipFeatures']->getErrors(),
                'count' => count($this->sheetInstances['MembershipFeatures']->getImportedMembershipFeatures())
            ],
            'classes' => [
                'imported' => $this->sheetInstances['Classes']->getImportedClasses(),
                'errors' => $this->sheetInstances['Classes']->getErrors(),
                'count' => count($this->sheetInstances['Classes']->getImportedClasses())
            ],
            'class_schedules' => [
                'imported' => $this->sheetInstances['ClassSchedules']->getImportedClassSchedules(),
                'errors' => $this->sheetInstances['ClassSchedules']->getErrors(),
                'count' => count($this->sheetInstances['ClassSchedules']->getImportedClassSchedules())
            ],
            'class_pricing' => [
                'imported' => $this->sheetInstances['ClassPricing']->getImportedClassPricing(),
                'errors' => $this->sheetInstances['ClassPricing']->getErrors(),
                'count' => count($this->sheetInstances['ClassPricing']->getImportedClassPricing())
            ],
            'services' => [
                'imported' => $this->sheetInstances['Services']->getImportedServices(),
                'errors' => $this->sheetInstances['Services']->getErrors(),
                'count' => count($this->sheetInstances['Services']->getImportedServices())
            ],
            'subscriptions' => [
                'imported' => $this->sheetInstances['Subscriptions']->getImportedSubscriptions(),
                'errors' => $this->sheetInstances['Subscriptions']->getErrors(),
                'count' => count($this->sheetInstances['Subscriptions']->getImportedSubscriptions())
            ],
        ];
        
        Log::info('Import results:', $results);
        return $results;
    }
}
