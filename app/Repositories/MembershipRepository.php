<?php 
namespace App\Repositories;

use App\Interfaces\MembershipRepositoryInterface;
use App\Models\Membership;

class MembershipRepository implements MembershipRepositoryInterface
{
    public function getAllMemberships()
    {
        return Membership::where('status', '1')->get();
    }

    public function createMembership(array $data)
    {
        return Membership::create($data);
    }

    public function updateMembership(Membership $membership , array $data)
    {
        $membership->update($data);
        return $membership;
    }

    public function deleteMembership(Membership $membership)
    {
        $membership->delete();
    }

    public function findById(int $id): ?Membership
    {
        return Membership::find($id);
    }
}