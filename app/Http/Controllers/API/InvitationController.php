<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invitation\StoreInvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Models\{Invitation, SiteSetting};
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};
use Exception;

class InvitationController extends Controller
{
    public function __construct(
        protected InvitationService $invitationService
    ) {}

    /**
     * Display the invitation form
     */
    public function index(SiteSetting $gym)
    {
        $user = Auth::guard('sanctum')->user();
        
        if (!$user) {
            return failureResponse('Please login to see your invitations.');
        }

        $subscription = $user->subscriptions()
            ->whereHas('branch', function($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })
            ->where('end_date', '>', now())
            ->where('status', 'active')
            ->with(['membership', 'branch'])
            ->first();

        if (!$subscription) {
            return failureResponse('You need an active subscription to send invitations.');
        }

        if (!$subscription->canSendInvitation()) {
            return failureResponse('You have reached your invitation limit for this membership.');
        }

        $invitationData = $this->invitationService->getUserInvitations($user, $gym);

        $data = [
            'invitations'   => InvitationResource::collection($invitationData),
            'is_subscribed' => $subscription ? true : false,
            'invitation_limit' => $subscription->membership->invitation_limit,
        ];
        return successResponse($data, 'Invitation form retrieved successfully');
    }

    /**
     * Store a new invitation
     */
    public function store(StoreInvitationRequest $request, SiteSetting $gym)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            
            $invitation = $this->invitationService->sendInvitation($request->validated(),$user,$gym);

            return successResponse(new InvitationResource($invitation), 'Invitation sent successfully! The recipient will receive an email with the QR code.');
        } catch (Exception $e) {
            Log::error('Error sending invitation: ' . $e->getMessage());
            return failureResponse($e->getMessage());
        }
    }

    /**
     * Verify invitation via QR code (for gym staff)
     */
    public function verify(Request $request, SiteSetting $gym)
    {
        $qrCode = $request->input('qr_code');
        
        if (!$qrCode) {
            return failureResponse('QR code is required');
        }

        try {
            $invitation = $this->invitationService->verifyAndUseInvitation($qrCode, Auth::guard('sanctum')->user());

            return successResponse(new InvitationResource($invitation),'Invitation verified and marked as used successfully!');
        } catch (Exception $e) {
            Log::error("Error verifying invite: ". $e->getMessage());
            return failureResponse($e->getMessage());
        }
    }

    /**
     * Resend an expired invitation
     */
    public function resend(SiteSetting $gym, Invitation $invitation)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            
            $invitation = $this->invitationService->resendInvitation($invitation, $user);

            return successResponse(new InvitationResource($invitation), 'Invitation resent successfully! The recipient will receive a new email with the updated QR code.');
        } catch (Exception $e) {
            Log::error("Error resending invite: ". $e->getMessage());
            return failureResponse($e->getMessage());
        }
    }

    /**
     * Delete an invitation
     */
    public function destroy(SiteSetting $gym, Invitation $invitation)
    {
        try {
            if ($invitation->inviter_id !== Auth::guard('sanctum')->user()->id) {
                return failureResponse('You are not authorized to delete this invitation');
            }

            if ($invitation->is_used) {
                return failureResponse('You cannot delete a used invitation');
            }

            $this->invitationService->deleteInvitation($invitation);
            return successResponse(message: 'Invitation deleted successfully');
        } catch (Exception $e) {
            Log::error("Error deleting invite: ". $e->getMessage());
            return failureResponse($e->getMessage());
        }
    }
}
