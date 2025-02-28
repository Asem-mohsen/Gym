<?php

namespace App\Http\Controllers\Web;

use App\Events\LockerUpdatedEvent;
use App\Http\Controllers\Controller;
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
        $lockers =  $this->lockerService->getLockers();

        return view('admin.lockers.index',compact('lockers'));
    }

    public function toggleLock(Request $request, Locker $locker)
    {
        $request->validate([
            'password' => 'nullable|string|min:4|max:10'
        ]);

        $response = $this->lockerService->toggleLocker($locker, $request->password);

        return response()->json($response);
    }
}
