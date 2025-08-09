<?php 
namespace App\Services;

use App\Events\LockerUpdatedEvent;
use App\Models\Locker;
use App\Repositories\LockerRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function lock(Locker $locker, string $password)
    {
        if ($locker->is_locked) {
            return ['success' => false, 'message' => 'Locker already locked'];
        }
        $locker = $this->lockerRepository->update($locker, [
            'is_locked' => true,
            'password' => Hash::make($password),
            'recovery_token' => null,
        ]);
        broadcast(new LockerUpdatedEvent($locker));
        return ['success' => true, 'locker' => $locker];
    }

    public function unlock(Locker $locker, string $password)
    {
        if (!$locker->is_locked) {
            return ['success' => false, 'message' => 'Locker already unlocked'];
        }
        if (!Hash::check($password, $locker->password)) {
            return ['success' => false, 'message' => 'Incorrect password'];
        }
        $locker = $this->lockerRepository->update($locker, [
            'is_locked' => false,
            'password' => null,
            'recovery_token' => null,
        ]);
        broadcast(new LockerUpdatedEvent($locker));
        return ['success' => true, 'locker' => $locker];
    }

    public function deleteLocker($locker)
    {
        return $this->lockerRepository->deleteLocker($locker);
    }

    public function generateRecoveryToken(Locker $locker)
    {
        $token = Str::random(32);
        $this->lockerRepository->update($locker, ['recovery_token' => Hash::make($token)]);
        return $token;
    }

    public function unlockWithRecoveryToken(Locker $locker, string $token)
    {
        if (!Hash::check($token, $locker->recovery_token)) {
            return ['success' => false, 'message' => 'Invalid recovery token'];
        }
        $locker = $this->lockerRepository->update($locker, [
            'is_locked' => false,
            'password' => null,
            'recovery_token' => null,
        ]);
        broadcast(new LockerUpdatedEvent($locker));
        return ['success' => true, 'locker' => $locker];
    }
}