<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membership\{ AddMembershipRequest , UpdateMembershipRequest};
use App\Models\Membership;
use App\Services\{MembershipService, SiteSettingService, FeatureService};
use Exception;

class MembershipController extends Controller
{
    protected int $siteSettingId;

    public function __construct(protected MembershipService $membershipService, protected SiteSettingService $siteSettingService, protected FeatureService $featureService)
    {
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $this->membershipService = $membershipService;
    }

    public function index()
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $this->siteSettingId ,withCount: ['payment']);
        $maxBookings = $memberships->max('payment_count');

        return view('admin.memberships.index', get_defined_vars());
    }

    public function create()
    {
        $features = $this->featureService->selectFeatures();
        return view('admin.memberships.create', get_defined_vars());
    }

    public function store(AddMembershipRequest $request)
    {
        try {
            $data = $request->validated();
            $data['site_setting_id'] = $this->siteSettingId;

            $membership = $this->membershipService->createMembership($data);
            
            if ($request->has('features')) {
                $membership->features()->sync($request->features);
            }

            return redirect()->route('membership.index')->with('success', 'Membership created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while adding a new membership, please try again in a few minutes.');
        }
    }

    public function show(Membership $membership)
    {
        // Security check: Ensure the membership belongs to the current gym
        if ($membership->site_setting_id !== $this->siteSettingId) {
            abort(404, 'Membership not found in this gym.');
        }

        $membership = $this->membershipService->showMembership($membership);

        return view('admin.memberships.show', get_defined_vars());
    }

    public function edit(Membership $membership)
    {
        // Security check: Ensure the membership belongs to the current gym
        if ($membership->site_setting_id !== $this->siteSettingId) {
            abort(404, 'Membership not found in this gym.');
        }

        $membership = $this->membershipService->showMembership($membership);
        $features = $this->featureService->selectFeatures();
        return view('admin.memberships.edit', get_defined_vars());
    }

    public function update(UpdateMembershipRequest $request , Membership $membership)
    {
        try {
            // Security check: Ensure the membership belongs to the current gym
            if ($membership->site_setting_id !== $this->siteSettingId) {
                abort(404, 'Membership not found in this gym.');
            }

            $data = $request->validated();
            $data['site_setting_id'] = $this->siteSettingId;

            $this->membershipService->updateMembership($membership, $data);
            
            if ($request->has('features')) {
                $membership->features()->sync($request->features);
            } else {
                $membership->features()->detach();
            }

            return redirect()->route('membership.index')->with('success', 'Membership updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating the membership, please try again in a few minutes.');
        }
    }

    public function destroy(Membership $membership)
    {
        try {
            // Security check: Ensure the membership belongs to the current gym
            if ($membership->site_setting_id !== $this->siteSettingId) {
                abort(404, 'Membership not found in this gym.');
            }

            $this->membershipService->deleteMembership($membership);
            return redirect()->route('membership.index')->with('success', 'Membership deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting the membership, please try again in a few minutes.');
        }
    }
}
