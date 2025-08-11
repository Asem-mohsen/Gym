<?php 
namespace App\Services;

use App\Repositories\MembershipRepository;

class MembershipService
{
    protected $membershipRepository;

    public function __construct(MembershipRepository $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    public function getMemberships(int $siteSettingId ,array $withCount = [])
    {
        return $this->membershipRepository->getAllMemberships(siteSettingId: $siteSettingId , with: ['offers', 'features'] , withCount: $withCount);
    }

    public function createMembership(array $data)
    {
        return $this->membershipRepository->createMembership($data);
    }

    public function updateMembership($membership, array $data)
    {
        return $this->membershipRepository->updateMembership($membership, $data);
    }

    public function showMembership($membership)
    {
        return $this->membershipRepository->findById($membership->id)->load('features', 'offers');
    }

    public function deleteMembership($membership)
    {
        return $this->membershipRepository->deleteMembership($membership);
    }
}