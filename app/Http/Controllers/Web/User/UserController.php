<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Models\SiteSetting;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected UserService $userService)
    {
        $this->userService = $userService;
    }


    public function index(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        return view('user.profile.index', compact('user', 'siteSetting'));
    }

    public function edit(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user', 'siteSetting'));
    }

    public function update(UpdateProfileRequest $request, SiteSetting $siteSetting)
    {
        try {
            $user = Auth::user();
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            $this->userService->updateUser($user, $data, $siteSetting->id);
            return redirect()->route('profile.index', ['siteSetting' => $siteSetting])->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Error happened while updating profile, please try again in a few minutes.');
        }
    }

    public function delete()
    {
        try {
            $user = Auth::user();
            $this->userService->deleteUser($user);
            Auth::logout();
            return redirect()->route('gym.selection')->with('success', 'Account deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting account, please try again in a few minutes.');
        }
    }
}
