<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invitation\StoreInvitationRequest;
use App\Models\Invitation;
use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class InvitationController extends Controller
{
    public function __construct(
        protected InvitationService $invitationService
    ) {}

    /**
     * Display the invitation form
     */
    public function create(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('user.login.index', ['siteSetting' => $siteSetting->slug])->with('error', 'Please login to send invitations.');
        }

        // Check if user has active subscription with invitation feature
        $subscription = $user->subscriptions()
            ->whereHas('branch', function($query) use ($siteSetting) {
                $query->where('site_setting_id', $siteSetting->id);
            })
            ->where('end_date', '>', now())
            ->where('status', 'active')
            ->with(['membership', 'branch'])
            ->first();

        if (!$subscription) {
            return redirect()->back()->with('error', 'You need an active subscription to send invitations.');
        }

        if (!$subscription->canSendInvitation()) {
            return redirect()->back()->with('error', 'You have reached your invitation limit for this membership.');
        }

        return view('user.invitations.create', compact('siteSetting', 'subscription'));
    }

    /**
     * Store a new invitation
     */
    public function store(StoreInvitationRequest $request, SiteSetting $siteSetting)
    {
        try {
            $user = Auth::user();
            
            $invitation = $this->invitationService->sendInvitation(
                $request->validated(),
                $user,
                $siteSetting
            );

            return redirect()->route('user.invitations.index', $siteSetting)
                ->with('success', 'Invitation sent successfully! The recipient will receive an email with the QR code.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display user's invitations
     */
    public function index(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('user.login.index', ['siteSetting' => $siteSetting->slug])->with('error', 'Please login to view your invitations.');
        }

        $invitationData = $this->invitationService->getUserInvitationsWithStatistics($user, $siteSetting);

        return view('user.invitations.index', array_merge($invitationData, compact('siteSetting')));
    }

    /**
     * Verify invitation via QR code (for gym staff)
     */
    public function verify(Request $request, SiteSetting $siteSetting)
    {
        $qrCode = $request->input('qr_code');
        
        if (!$qrCode) {
            return view('user.invitations.verify', compact('siteSetting'));
        }

        try {
            $invitation = $this->invitationService->verifyAndUseInvitation($qrCode, Auth::user());

            return view('user.invitations.verify', compact('siteSetting', 'invitation'))
                ->with('success', 'Invitation verified and marked as used successfully!');
        } catch (Exception $e) {
            return view('user.invitations.verify', compact('siteSetting'))
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Scan QR code and automatically verify invitation
     */
    public function scanAndVerify(SiteSetting $siteSetting, string $qrCode)
    {
        try {
            $user = Auth::user();
            
            $invitation = $this->invitationService->verifyAndUseInvitation($qrCode, $user);

            return view('user.invitations.verify', compact('siteSetting', 'invitation'))->with('success', 'Invitation verified and marked as used successfully! Guest: ' . $invitation->invitee_email);
        } catch (Exception $e) {
            return view('user.invitations.verify', compact('siteSetting'))->with('error', $e->getMessage());
        }
    }

    /**
     * Resend an expired invitation
     */
    public function resend(SiteSetting $siteSetting, Invitation $invitation)
    {
        try {
            $user = Auth::user();
            
            $this->invitationService->resendInvitation($invitation, $user);

            return redirect()->route('user.invitations.index', $siteSetting)->with('success', 'Invitation resent successfully! The recipient will receive a new email with the updated QR code.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
