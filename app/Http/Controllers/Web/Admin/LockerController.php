<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locker\LockLockerRequest;
use App\Http\Requests\Locker\UnlockLockerRequest;
use App\Models\Locker;
use App\Services\LockerService;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    public function __construct(protected LockerService $lockerService)
    {
        $this->lockerService = $lockerService;
    }

    public function index()
    {
        $lockers = $this->lockerService->getLockers();
        return view('admin.lockers.index', compact('lockers'));
    }

    // Admin: Lock a locker with a password
    public function lock(LockLockerRequest $request, Locker $locker)
    {
        $response = $this->lockerService->lock($locker, $request->password);
        return response()->json($response);
    }

    // Admin: Unlock a locker with a password
    public function unlock(UnlockLockerRequest $request, Locker $locker)
    {
        $response = $this->lockerService->unlock($locker, $request->password);
        return response()->json($response);
    }

    // Admin: Generate a recovery token for a locker
    public function generateRecoveryToken(Request $request, Locker $locker)
    {
        $token = $this->lockerService->generateRecoveryToken($locker);
        return response()->json([
            'success' => true,
            'recovery_token' => $token,
            'message' => 'Recovery token generated. Please copy and use it securely.'
        ]);
    }

    // Admin: Unlock a locker using a recovery token
    public function unlockWithToken(Request $request, Locker $locker)
    {
        $request->validate(['recovery_token' => 'required|string']);
        $response = $this->lockerService->unlockWithRecoveryToken($locker, $request->recovery_token);
        return response()->json($response);
    }
}
