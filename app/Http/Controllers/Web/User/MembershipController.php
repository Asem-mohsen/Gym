<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\{ Membership, SiteSetting};
use App\Services\MembershipService;

class MembershipController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $siteSetting->id);

        return view('user.memberships.index', get_defined_vars());
    }
    public function show(SiteSetting $siteSetting, Membership $membership)
    {
        $membership = $this->membershipService->showMembership($membership);

        return view('user.memberships.show', get_defined_vars());
    }
}
