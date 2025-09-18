<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        $this->userService = $userService;
    }


    public function profile()
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $user = $this->userService->showUser($user, [
                'bookings.bookable', 
                'publicPhotos.media',
                'subscriptions.membership',
                'subscriptions.branch.phones',
                'trainerInformation'
            ]);
            return successResponse(new UserResource($user), 'user data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving user, please try again.');
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            /**
             * @var User $user
            */
            $user = Auth::guard('sanctum')->user();
            $user = $this->userService->updateUser($user, $request->validated(), $user->getCurrentSite()->id);
            
            $user = $this->userService->showUser($user, [
                'bookings.bookable', 
                'publicPhotos.media',
                'subscriptions.membership',
                'subscriptions.branch.phones',
                'trainerInformation'
            ]);
            
            return successResponse(new UserResource($user), 'user updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error updating user, please try again.');
        }
    }

    public function destroy()
    {
        try {
            /**
             * @var User $user
            */
            $user = Auth::guard('sanctum')->user();
            
            $gym = $user->getCurrentSite();

            $this->userService->deleteUser($user, $gym);
            
            return successResponse(message: 'Account deleted successfully. You have been logged out.');
        } catch (Exception $e) {
            return failureResponse('Error deleting user, please try again.');
        }
    }
}
