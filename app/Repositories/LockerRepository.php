<?php 
namespace App\Repositories;

use App\Models\Locker;

class LockerRepository
{
    public function getLockers()
    {
        return Locker::all();
    }

    public function find(int $id)
    {
        return Locker::findOrFail($id);
    }

    public function update(Locker $locker , array $data)
    {
        $locker->update($data);
        return $locker;
    }

    public function deleteLocker(Locker $locker)
    {
        $locker->delete();
    }

    public function findByRecoveryToken($token)
    {
        return Locker::where('recovery_token', $token)->first();
    }

}