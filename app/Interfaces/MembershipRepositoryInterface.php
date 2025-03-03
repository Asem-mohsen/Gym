<?php

namespace App\Interfaces;

use App\Models\Membership;

interface MembershipRepositoryInterface
{
    public function getAllMemberships();

    public function createMembership(array $data);

    public function updateMembership(Membership $membership, array $data);

    public function deleteMembership(Membership $membership);

    public function findById(int $id);
}
