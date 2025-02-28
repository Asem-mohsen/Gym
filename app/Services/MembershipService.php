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

    public function getMemberships(array $withCount = [])
    {
        return $this->membershipRepository->getAllMemberships(with: ['offers'] , withCount: $withCount);
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
        return $this->membershipRepository->findById($membership->id);
    }

    public function deleteMembership($membership)
    {
        return $this->membershipRepository->deleteMembership($membership);
    }
}