<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\{SiteSetting, User};
use App\Services\{CheckinService, QrCodeService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    public function __construct(
        private CheckinService $checkinService,
        private QrCodeService $qrCodeService
    ) {}

    /**
     * Show self-check-in page
     */
    public function showSelfCheckin(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login.index', ['siteSetting' => $siteSetting->slug]);
        }

        // Check if gym has check-in settings
        $checkinSettings = $siteSetting->checkinSettings()->first();

        if (!$checkinSettings || !$checkinSettings->enable_self_scan) {
            return redirect()->back()->with('error', 'Self-scan check-in is not available for this gym.');
        }

        // Validate user can check in
        $validation = $this->checkinService->validateCheckin($user, $siteSetting, 'self_scan');

        return view('user.checkin.self', compact('siteSetting', 'user', 'validation'));
    }

    /**
     * Process self-check-in
     */
    public function processSelfCheckin(Request $request, SiteSetting $siteSetting)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login.index', ['siteSetting' => $siteSetting->slug]);
        }

        // Check if gym has check-in settings
        $checkinSettings = $siteSetting->checkinSettings()->first();
        if (!$checkinSettings || !$checkinSettings->enable_self_scan) {
            return redirect()->back()->with('error', 'Self-scan check-in is not available for this gym.');
        }

        // Validate user can check in
        $validation = $this->checkinService->validateCheckin($user, $siteSetting, 'self_scan');
        
        if ($validation['code'] !== 'VALID') {
            return redirect()->back()->with('error', $validation['message']);
        }

        // Log the check-in
        $this->checkinService->logVisit($user->id, $siteSetting->id, 'self_scan', $request);

        return redirect()->back()->with('success', 'Check-in successful! Welcome to ' . $siteSetting->gym_name);
    }

    /**
     * Show user's personal QR code
     */
    public function showPersonalQr(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login.index', ['siteSetting' => $siteSetting->slug]);
        }

        // Check if gym has check-in settings
        $checkinSettings = $siteSetting->checkinSettings()->first();
        if (!$checkinSettings || !$checkinSettings->enable_gate_scan) {
            return redirect()->back()->with('error', 'Gate-scan check-in is not available for this gym.');
        }

        $qrToken = $this->qrCodeService->generatePersonalQrUrl($user, $siteSetting);
        $qrUrl = $this->qrCodeService->generatePersonalQrUrlWithEndpoint($user, $siteSetting);
        $qrData = $this->qrCodeService->generateQrData($qrToken);

        return view('user.checkin.personal-qr', compact('siteSetting', 'user', 'qrUrl', 'qrToken', 'qrData'));
    }

    /**
     * Show staff scanner interface
     */
    public function showStaffScanner(SiteSetting $gym)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login.index', ['siteSetting' => $gym->slug]);
        }

        // Check if user has permission to use staff scanner
        if (!$user->hasGymPermission($gym->id, 'manage_checkin_settings')) {
            return redirect()->back()->with('error', 'You do not have permission to access the staff scanner.');
        }

        // Check if gym has check-in settings
        $checkinSettings = $gym->checkinSettings()->first();
        if (!$checkinSettings || !$checkinSettings->enable_gate_scan) {
            return redirect()->back()->with('error', 'Gate-scan check-in is not available for this gym.');
        }

        $branches = $gym->branches;
        $recentCheckins = $this->checkinService->getRecentCheckins($gym->id, 10);

        return view('user.checkin.staff-scanner', compact('gym', 'user', 'branches', 'recentCheckins'));
    }

    /**
     * Process gate check-in (staff scanning user QR)
     */
    public function processGateCheckin(Request $request, SiteSetting $gym)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login.index', ['siteSetting' => $gym->slug]);
        }

        // Check if user has permission to use staff scanner
        if (!$user->hasGymPermission('view_checkin_settings', $gym->id)) {
            return redirect()->back()->with('error', 'You do not have permission to access the staff scanner.');
        }

        $request->validate([
            'qr_token' => 'required|string',
            'branch_id' => 'nullable|exists:branches,id'
        ]);

        // Decrypt and validate QR token
        $decryptedData = $this->qrCodeService->decryptQrToken($request->qr_token);
        
        if (!$decryptedData) {
            return redirect()->back()->with('error', 'Invalid QR code. Please try again.');
        }

        $targetUserId = $decryptedData['user_id'];
        $targetUser = User::find($targetUserId);

        if (!$targetUser) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Validate user can check in
        $validation = $this->checkinService->validateCheckin($targetUser, $gym->id, 'gate_scan');
        
        if ($validation['code'] !== 'VALID') {
            return redirect()->back()->with('error', $validation['message']);
        }

        // Log the check-in
        $metadata = [
            'scanned_by' => $user->id,
            'scanned_by_name' => $user->name,
            'branch_id' => $request->branch_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        $this->checkinService->logVisit($targetUserId, $gym->id, 'gate_scan', $request, $metadata);

        return redirect()->back()->with('success', 'Check-in successful for ' . $targetUser->name);
    }

    /**
     * Show check-in history
     */
    public function showCheckinHistory(SiteSetting $siteSetting)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login.index', ['siteSetting' => $siteSetting->slug]);
        }

        $history = $this->checkinService->getUserCheckinHistory($user->id, $siteSetting->id, 50);

        return view('user.checkin.history', compact('siteSetting', 'user', 'history'));
    }

    /**
     * Show check-in statistics (for staff/admin)
     */
    public function showCheckinStats(SiteSetting $siteSetting)
    {
        /**
         * @var User $user
        */

        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login.index', ['siteSetting' => $siteSetting->slug]);
        }

        // Check if user has permission to view stats
        if (!$user->hasGymPermission('view_checkin_settings', $siteSetting->id)) {
            return redirect()->back()->with('error', 'You do not have permission to view check-in statistics.');
        }

        $period = request()->get('period', 'today');
        $stats = $this->checkinService->getGymCheckinStats($siteSetting->id, $period);
        $recentCheckins = $this->checkinService->getRecentCheckins($siteSetting->id, 20);

        return view('user.checkin.stats', compact('gym', 'user', 'stats', 'recentCheckins', 'period'));
    }
}
