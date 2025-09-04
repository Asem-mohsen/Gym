<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Models\{SiteSetting, User};
use App\Services\{UserService, UserPhotoService};
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected int $siteSettingId;

    public function __construct(
        protected UserService $userService,
        protected UserPhotoService $userPhotoService
    ) {
        $this->userService = $userService;
        $this->userPhotoService = $userPhotoService;
    }


    public function index(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        /** @var User $user */
        $user->load('publicPhotos');
        return view('user.profile.index', compact('user', 'siteSetting'));
    }

    public function edit(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        /** @var User $user */
        $user->load(['photos' => function($query) {
            $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }]);
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
            
            // Handle photo uploads
            if ($request->hasFile('photos') || $request->has('delete_photos') || $request->has('photo_titles')) {
                $this->userPhotoService->handlePhotoUploads($user, $data);
            }
            
            $this->userService->updateUser($user, $data, $siteSetting->id);
            return redirect()->route('profile.index', ['siteSetting' => $siteSetting])->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating profile, please try again in a few minutes.');
        }
    }

    public function delete(SiteSetting $siteSetting)
    {
        try {
            $user = Auth::user();

            $this->userService->deleteUser($user, $siteSetting);

            Auth::logout();

            return redirect()->route('gym.selection')->with('success', 'Account deleted successfully. We\'ll miss you!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting account, please try again in a few minutes.');
        }
    }
}
