<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\GymContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GymContextController extends Controller
{
    public function __construct(private GymContextService $gymContextService)
    {
        $this->gymContextService = $gymContextService;
    }

    /**
     * Get current gym context for the user
     */
    public function getCurrentContext(Request $request)
    {
        try {
            $gymContext = $this->gymContextService->getCurrentGymContext();
            
            if (!$gymContext) {
                return failureResponse('No gym context found. Please visit a gym page first.', 404);
            }

            return successResponse($gymContext, 'Gym context retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving gym context, please try again.', 500);
        }
    }

    /**
     * Update gym context for the user
     */
    public function updateContext(Request $request)
    {
        try {
            $request->validate([
                'gym_id' => 'required|integer|exists:site_settings,id',
                'gym_slug' => 'required|string',
                'gym_name' => 'required|string',
                'gym_logo' => 'required|string'
            ]);

            $this->gymContextService->updateGymContext(
                $request->gym_id,
                $request->gym_slug,
                $request->gym_name,
                $request->gym_logo
            );

            // If user is authenticated, also update API context
            if (Auth::guard('sanctum')->check()) {
                $this->gymContextService->storeGymContextForApi(
                    Auth::id(),
                    $request->gym_id
                );
            }

            return successResponse(message: 'Gym context updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error updating gym context, please try again.', 500);
        }
    }

    /**
     * Clear current gym context
     */
    public function clearContext(Request $request)
    {
        try {
            $this->gymContextService->clearGymContext();

            // If user is authenticated, also clear API context
            if (Auth::guard('sanctum')->check()) {
                // Remove the cached gym context for API users
                $cacheKey = "user_" . Auth::id() . "_current_gym";
                cache()->forget($cacheKey);
            }

            return successResponse(message: 'Gym context cleared successfully');
        } catch (Exception $e) {
            return failureResponse('Error clearing gym context, please try again.', 500);
        }
    }

    /**
     * Validate if user is in correct gym context
     */
    public function validateContext(Request $request)
    {
        try {
            $request->validate([
                'gym_id' => 'required|integer'
            ]);

            $isValid = $this->gymContextService->validateGymContext($request->gym_id);

            return successResponse([
                'is_valid' => $isValid
            ], 'Gym context validation completed');
        } catch (Exception $e) {
            return failureResponse('Error validating gym context, please try again.', 500);
        }
    }
}
