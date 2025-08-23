<?php

namespace App\Exports;

use App\Models\SiteSetting;
use App\Services\Deletations\GymDeactivationService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GymDataExport implements WithMultipleSheets
{
    protected $gym;
    protected $gymData;

    public function __construct(SiteSetting $gym)
    {
        $this->gym = $gym;
        $deactivationService = new GymDeactivationService();
        $this->gymData = $deactivationService->getGymDataForExport($gym);
    }

    public function sheets(): array
    {
        return [
            'Gym_Info' => new GymInfoSheet($this->gymData['gym_info']),
            'Users' => new UsersSheet($this->gymData['users']),
            'Branches' => new BranchesSheet($this->gymData['branches']),
            'Services' => new ServicesSheet($this->gymData['services']),
            'Classes' => new ClassesSheet($this->gymData['classes']),
            'Memberships' => new MembershipsSheet($this->gymData['memberships']),
            'Offers' => new OffersSheet($this->gymData['offers']),
            'Payments' => new PaymentsSheet($this->gymData['payments']),
            'Invitations' => new InvitationsSheet($this->gymData['invitations']),
            'Blog_Posts' => new BlogPostsSheet($this->gymData['blog_posts']),
            'Comments' => new CommentsSheet($this->gymData['comments']),
            'Bookings' => new BookingsSheet($this->gymData['bookings']),
            'Contacts' => new ContactsSheet($this->gymData['contacts']),
            'Lockers' => new LockersSheet($this->gymData['lockers']),
            'Coaching_Sessions' => new CoachingSessionsSheet($this->gymData['coaching_sessions']),
            'Transactions' => new TransactionsSheet($this->gymData['transactions']),
            'Trainer_Information' => new TrainerInformationSheet($this->gymData['trainer_information']),
            'Roles' => new RolesSheet($this->gymData['roles']),
            'Documents' => new DocumentsSheet($this->gymData['documents']),
            'Score_Data' => new ScoreDataSheet($this->gymData['score_data']),
        ];
    }
}

// Individual sheet classes
class GymInfoSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return [$this->data];
    }

    public function headings(): array
    {
        return array_keys($this->data);
    }
}

class UsersSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class BranchesSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class ServicesSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class ClassesSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class MembershipsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class OffersSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class PaymentsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class InvitationsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class BlogPostsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class CommentsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}


class BookingsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class ContactsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class LockersSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class CoachingSessionsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class TransactionsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class TrainerInformationSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class RolesSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class DocumentsSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}

class ScoreDataSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->data[0] ? array_keys($this->data[0]) : [];
    }
}
