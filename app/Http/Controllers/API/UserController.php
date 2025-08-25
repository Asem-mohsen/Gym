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
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userService;
    protected $siteSettingId;

    public function __construct(UserService $userService, SiteSettingService $siteSettingService)
    {
        $this->userService = $userService;
        $this->siteSettingId = $siteSettingService->getCurrentSiteSettingId();
    }

    public function index(User $user , SiteSetting $gym)
    {
        try {
            $users = $this->userService->getUsers(siteSettingId: $gym->id);
            return successResponse(compact('users'), 'users data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving users, please try again.');
        }
    }

    public function trainers(SiteSetting $gym)
    {
        try {
            $trainers = $this->userService->getTrainers(siteSettingId: $gym->id);
            return successResponse(compact('trainers'), 'trainers data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving trainers, please try again.');
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

    public function profile(SiteSetting $gym, User $user)
    {
        try {
            $user = $this->userService->showUser($user, ['bookings.bookable']);
            return successResponse($user, 'user data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving user, please try again.');
        }
    }

    public function coachProfile(SiteSetting $gym, User $user)
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

    public function update(UpdateUserRequest $request ,SiteSetting $gym, User $user)
    {
        try {
            $user = $this->userService->updateUser($user ,$request->validated(), $gym->id);
            return successResponse(data: $user, message: 'user updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error updating user, please try again.');
        }
    }

    public function destroy(SiteSetting $gym, User $user)
    {
        try {
            if($user->id != Auth::guard('sanctum')->user()->id) return failureResponse('You are not allowed to delete this user', 403);
            
            // Get current authenticated user ID before deletion        
            $currentUserId = Auth::guard('sanctum')->user()->id;
            
            // Delete user account and get gym info for email
            $gym = $this->userService->deleteUser($user, $gym);
            
            // Logout the user from all sessions by deleting tokens
            DB::table('personal_access_tokens')
                ->where('tokenable_id', $currentUserId)
                ->where('tokenable_type', User::class)
                ->delete();
            
            return successResponse(message: 'Account deleted successfully. You have been logged out.');
        } catch (Exception $e) {
            return failureResponse('Error deleting user, please try again.');
        }
    }
}
