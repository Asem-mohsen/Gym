<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Invitation, Branch, Membership};
use App\Services\{BranchService, InvitationService, MembershipService, SiteSettingService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    protected int $siteSettingId;
    public function __construct(
        private InvitationService $invitationService,
        private SiteSettingService $siteSettingService,
        private BranchService $branchService,
        private MembershipService $membershipService
    ) {
        $this->invitationService = $invitationService;
        $this->branchService = $branchService;
        $this->membershipService = $membershipService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        $invitations = $this->invitationService->getInvitations($this->siteSettingId, $request->all());

        $branches = $this->branchService->getBranches($this->siteSettingId);
        $memberships = $this->membershipService->getMemberships($this->siteSettingId);

        return view('admin.invitations.index', compact('invitations', 'branches', 'memberships'));
    }

    public function show(Invitation $invitation)
    {
        $invitation->load(['inviter', 'gym', 'membership', 'usedBy']);
        
        return view('admin.invitations.show', compact('invitation'));
    }

    private function getCurrentSiteSettingId(): int
    {
        return Auth::user()->siteSettingId ?? 1;
    }
}
