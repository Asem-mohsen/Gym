<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{SiteSetting, User};
use App\Services\{CheckinService, QrCodeService};
use Illuminate\Support\Facades\{Auth, Validator};
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function __construct(
        private CheckinService $checkinService,
        private QrCodeService $qrCodeService
    ) {}

    /**
     * Self-scan check-in (user scans gym's QR code)
     */
    public function selfCheckin(Request $request, SiteSetting $gym)
    {
        try {
            // Ensure user is authenticated
            if (!Auth::check()) {
                return failureResponse('Authentication required for check-in.', 401);
            }

            $user = Auth::user();

            // Validate check-in
            $validation = $this->checkinService->validateCheckin($user->id, $gym->id);

            if (!$validation['valid']) {
                return failureResponse($validation['message'], 400);
            }

            // Check if user has already checked in recently (prevent spam - per user only)
            if ($this->checkinService->hasRecentCheckin($user->id, $gym->id)) {
                return failureResponse('You have already checked in recently. Please wait a moment.', 429);
            }

            // Log the check-in
            $checkin = $this->checkinService->logVisit(
                userId: $user->id,
                gymId: $gym->id,
                checkinType: 'self_scan',
                request: $request
            );

            $message = $validation['warning'] ?? false 
                ? 'Check-in recorded. You have already checked in today.'
                : 'Check-in successful! Welcome to ' . $gym->name;

            return successResponse([
                'checkin' => $checkin,
                'user' => $user->only(['id', 'name', 'email']),
                'gym' => $gym->only(['id', 'name', 'slug']),
                'message' => $message,
            ], $message);

        } catch (\Exception $e) {
            return failureResponse('Check-in failed. Please try again.', 500);
        }
    }

    /**
     * Gate-scan check-in (gym scans user's personal QR code)
     */
    public function gateCheckin(Request $request, SiteSetting $gym)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required|string',
                'branch_id' => 'nullable|exists:branches,id',
            ]);

            if ($validator->fails()) {
                return failureResponse('Invalid request data.', 400);
            }

            // Decrypt and validate the QR token
            $tokenData = $this->qrCodeService->decryptPersonalQrToken($request->token);

            if (!$tokenData) {
                return failureResponse('Invalid or expired QR code.', 400);
            }

            // Verify the token is for this gym
            if ($tokenData['gym_id'] != $gym->id) {
                return failureResponse('QR code is not valid for this gym.', 400);
            }

            $user = User::find($tokenData['user_id']);

            if (!$user) {
                return failureResponse('User not found.', 404);
            }

            // Validate check-in
            $validation = $this->checkinService->validateCheckin($user->id, $gym->id);

            if (!$validation['valid']) {
                return failureResponse($validation['message'], 400);
            }

            // Check if user has already checked in recently (prevent spam - per user only)
            if ($this->checkinService->hasRecentCheckin($user->id, $gym->id)) {
                return failureResponse('User has already checked in recently.', 429);
            }

            // Log the check-in
            $checkin = $this->checkinService->logVisit(
                userId: $user->id,
                gymId: $gym->id,
                checkinType: 'gate_scan',
                request: $request
            );

            $message = $validation['warning'] ?? false 
                ? 'Check-in recorded. User has already checked in today.'
                : 'Check-in successful! Welcome ' . $user->name;

            return successResponse([
                'checkin' => $checkin,
                'user' => $user->only(['id', 'name', 'email']),
                'gym' => $gym->only(['id', 'name', 'slug']),
                'message' => $message,
            ], $message);

        } catch (\Exception $e) {
            return failureResponse('Check-in failed. Please try again.', 500);
        }
    }

    /**
     * Get user's personal QR code
     */
    public function getPersonalQr(Request $request, SiteSetting $gym)
    {
        try {
            if (!Auth::check()) {
                return failureResponse('Authentication required.', 401);
            }

            /**
             * @var User $user
             */
            $user = Auth::user();

            // Verify user is associated with this gym
            if (!$user->gyms()->where('site_setting_id', $gym->id)->exists()) {
                return failureResponse('You are not associated with this gym.', 403);
            }

            $qrUrl = $this->qrCodeService->generatePersonalQrUrl($user, $gym);
            $qrData = $this->qrCodeService->generateQrData($qrUrl);

            return successResponse([
                'qr_url' => $qrUrl,
                'qr_data' => $qrData,
                'user' => $user->only(['id', 'name', 'email']),
                'gym' => $gym->only(['id', 'name', 'slug']),
            ], 'Personal QR code generated successfully.');

        } catch (\Exception $e) {
            return failureResponse('Failed to generate QR code.', 500);
        }
    }

    /**
     * Get check-in statistics for a gym (admin/staff only)
     */
    public function getCheckinStats(Request $request, SiteSetting $gym)
    {
        try {
            if (!Auth::check()) {
                return failureResponse('Authentication required.', 401);
            }

            /**
             * @var User $user
             */
            $user = Auth::user();

            // Check if user has permission to view stats
            if (!$user->isAdmin() && !$user->hasGymPermission('view_checkin_stats', $gym->id)) {
                return failureResponse('Insufficient permissions.', 403);
            }

            $period = $request->get('period', 'today');
            $stats = $this->checkinService->getGymCheckinStats($gym->id, $period);

            return successResponse([
                'stats' => $stats,
                'gym' => $gym->only(['id', 'name', 'slug']),
            ], 'Check-in statistics retrieved successfully.');

        } catch (\Exception $e) {
            return failureResponse('Failed to retrieve statistics.', 500);
        }
    }

    /**
     * Get user's check-in history
     */
    public function getUserCheckinHistory(Request $request, SiteSetting $siteSetting)
    {
        try {
            if (!Auth::check()) {
                return failureResponse('Authentication required.', 401);
            }

            /**
             * @var User $user
             */
            $user = Auth::user();

            // Verify user is associated with this gym
            if (!$user->gyms()->where('site_setting_id', $siteSetting->id)->exists()) {
                return failureResponse('You are not associated with this gym.', 403);
            }

            $limit = $request->get('limit', 10);
            $history = $this->checkinService->getUserCheckinHistory($user->id, $siteSetting->id, $limit);

            return successResponse([
                'history' => $history,
                'user' => $user->only(['id', 'name', 'email']),
                'gym' => $siteSetting->only(['id', 'name', 'slug']),
            ], 'Check-in history retrieved successfully.');

        } catch (\Exception $e) {
            return failureResponse('Failed to retrieve check-in history.', 500);
        }
    }

    /**
     * Validate a QR token without performing check-in
     */
    public function validateQrToken(Request $request, SiteSetting $gym)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return failureResponse('Invalid request data.', 400);
            }

            $tokenData = $this->qrCodeService->decryptPersonalQrToken($request->token);

            if (!$tokenData) {
                return failureResponse('Invalid or expired QR code.', 400);
            }

            if ($tokenData['gym_id'] != $gym->id) {
                return failureResponse('QR code is not valid for this gym.', 400);
            }

            $user = User::find($tokenData['user_id']);

            if (!$user) {
                return failureResponse('User not found.', 404);
            }

            $validation = $this->checkinService->validateCheckin($user->id, $gym->id);

            return successResponse([
                'user' => $user->only(['id', 'name', 'email']),
                'validation' => $validation,
                'can_checkin' => $validation['valid'] && !($validation['warning'] ?? false),
            ], 'QR token validated successfully.');

        } catch (\Exception $e) {
            return failureResponse('Failed to validate QR token.', 500);
        }
    }
}
