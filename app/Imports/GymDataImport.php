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

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function sheets(): array
    {
        return [
            'Users' => new UsersImport($this->siteSettingId),
            'Branches' => new BranchesImport($this->siteSettingId),
            'Memberships' => new MembershipsImport($this->siteSettingId),
            'Classes' => new ClassesImport($this->siteSettingId),
            'Services' => new ServicesImport($this->siteSettingId),
        ];
    }

    public function getImportResults(): array
    {
        return [
            'users' => [
                'imported' => $this->sheets()['Users']->getImportedUsers(),
                'errors' => $this->sheets()['Users']->getErrors(),
                'count' => count($this->sheets()['Users']->getImportedUsers())
            ],
            'branches' => [
                'imported' => $this->sheets()['Branches']->getImportedBranches(),
                'errors' => $this->sheets()['Branches']->getErrors(),
                'count' => count($this->sheets()['Branches']->getImportedBranches())
            ],
            'memberships' => [
                'imported' => $this->sheets()['Memberships']->getImportedMemberships(),
                'errors' => $this->sheets()['Memberships']->getErrors(),
                'count' => count($this->sheets()['Memberships']->getImportedMemberships())
            ],
            'classes' => [
                'imported' => $this->sheets()['Classes']->getImportedClasses(),
                'errors' => $this->sheets()['Classes']->getErrors(),
                'count' => count($this->sheets()['Classes']->getImportedClasses())
            ],
            'services' => [
                'imported' => $this->sheets()['Services']->getImportedServices(),
                'errors' => $this->sheets()['Services']->getErrors(),
                'count' => count($this->sheets()['Services']->getImportedServices())
            ],
        ];
    }
}
