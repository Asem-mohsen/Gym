<?php

namespace App\Policies;

use App\Models\User;

class LockerPolicy
{
    public function adminUnlock(User $user)
    {
        return $user->isAdmin();
    }
}
