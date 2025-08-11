<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\{ Membership, SiteSetting};
use App\Services\{MembershipService, UserService};

class MembershipController extends Controller
{
    protected int $siteSettingId;

    public function __construct(
        protected MembershipService $membershipService,
        protected UserService $userService
    ) {
        $this->membershipService = $membershipService;
        $this->userService = $userService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $siteSetting->id);

        return view('user.memberships.index', get_defined_vars());
    }
    
    public function show(SiteSetting $siteSetting, Membership $membership)
    {
        $membership = $this->membershipService->showMembership($membership);
        $trainers = $this->userService->getTrainers(siteSettingId: $siteSetting->id);

        return view('user.memberships.show', get_defined_vars());
    }

    public function success(SiteSetting $siteSetting)
    {
        $paymentIntentId = request()->query('payment_intent');
        
        return view('user.memberships.success', compact('siteSetting', 'paymentIntentId'));
    }
}
