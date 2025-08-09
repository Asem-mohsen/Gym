<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Users\{ UpdateUserRequest , AddUserRequest};
use App\Models\User;
use App\Models\SiteSetting;
use App\Services\SiteSettingService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;
    protected $siteSettingId;

    public function __construct(UserService $userService, SiteSettingService $siteSettingService)
    {
        $this->userService = $userService;
        $this->siteSettingId = $siteSettingService->getCurrentSiteSettingId();
    }

    public function index()
    {
        try {
            $users = $this->userService->getUsers(siteSettingId: $this->siteSettingId);
            return successResponse(compact('users'), 'users data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving users, please try again.');
        }
    }

    public function trainers()
    {
        try {
            $trainers = $this->userService->getTrainers(siteSettingId: $this->siteSettingId);
            return successResponse(compact('trainers'), 'trainers data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving trainers, please try again.');
        }
    }

    public function editByAdmin(Request $request , User $user)
    {
        try {
            $this->userService->showUser($user);
            $user->load('role');
            return successResponse(compact('user'), 'user data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving user, please try again.');
        }
    }

    public function edit(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            return successResponse(compact('user'), 'user data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving user, please try again.');
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $user->load('bookings.bookable');
            return successResponse(compact('user'), 'user data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving user, please try again.');
        }
    }

    public function coachProfile(User $user, SiteSetting $gym)
    {
        try {
            // Check if the user belongs to the specified gym
            if (!$user->gyms()->where('site_setting_id', $gym->id)->exists()) {
                return failureResponse('Invalid coach or gym', 400);
            }
            
            $user = $this->userService->showUser($user);
            return successResponse(compact('user'), 'coach data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving coach data, please try again.');
        }
    }

    public function addUsers(AddUserRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated(), $this->siteSettingId);
            return successResponse(compact('user'), $user->name . ' created successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating user, please try again.');
        }
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $user = $this->userService->updateUser($user ,$request->validated(), $this->siteSettingId);
            return successResponse(message: 'user updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error updating user, please try again.');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $this->userService->deleteUser($user );
            return successResponse(message: 'user deleted successfully');
        } catch (Exception $e) {
            return failureResponse('Error deleting user, please try again.');
        }
    }
}
