<?php 
namespace App\Services;

use App\Events\LockerUpdatedEvent;
use App\Models\Locker;
use App\Repositories\LockerRepository;
use Illuminate\Support\Facades\Hash;

class LockerService
{
    public function __construct(protected LockerRepository $lockerRepository)
    {
        $this->lockerRepository = $lockerRepository;
    }

    public function getLockers()
    {
        return $this->lockerRepository->getLockers();
    }

    public function toggleLocker(Locker $locker, ?string $password)
    {
        $locker = $this->lockerRepository->find($locker->id);

        if ($locker->is_locked) {
            // Validate password
            if (!Hash::check($password, $locker->password)) {
                return ['success' => false, 'message' => 'Incorrect password'];
            }
            // Unlock the locker
            $locker = $this->lockerRepository->update($locker, [
                'is_locked' => false,
                'password' => null
            ]);
        } else {
            // Lock the locker
            $locker = $this->lockerRepository->update($locker, [
                'is_locked' => true,
                'password' => Hash::make($password)
            ]);
        }

        broadcast(new LockerUpdatedEvent($locker));
        return ['success' => true, 'locker' => $locker];
    }

    public function deleteLocker($locker)
    {
        return $this->lockerRepository->deleteLocker($locker);
    }
}