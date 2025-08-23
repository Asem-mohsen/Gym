<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\AddSubscriptionRequest;
use App\Models\Subscription;
use App\Services\{ BranchService , SubscriptionService , MembershipService, OfferService, SiteSettingService, UserService};
use Exception;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService ,
        protected UserService $userService,
        protected MembershipService $membershipService,
        protected BranchService $branchService,
        protected SiteSettingService $siteSettingService,
        protected OfferService $offerService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->userService = $userService;
        $this->membershipService = $membershipService;
        $this->branchService = $branchService;
        $this->offerService = $offerService;
        $this->siteSettingService = $siteSettingService;
    }

    public function index(Request $request)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $perPage = $request->get('per_page', 15);
        $branchId = $request->get('branch_id');
        $search = $request->get('search');
        [$subscriptions, $counts] = $this->subscriptionService->getSubscriptions($siteSettingId, $perPage, $branchId, $search);
        
        $branches = $this->branchService->getBranches($siteSettingId);
        
        return view('admin.subscriptions.index', compact('subscriptions', 'counts', 'branches'));
    }

    public function create()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $memberships = $this->membershipService->getMemberships(siteSettingId: $siteSettingId);
        $users    = $this->userService->getUsers($siteSettingId );
        $branches = $this->branchService->getBranches($siteSettingId);

        return view('admin.subscriptions.create',get_defined_vars());
    }

    public function edit(Request $request , Subscription $subscription)
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $memberships = $this->membershipService->getMemberships(siteSettingId: $siteSettingId);
        $users    = $this->userService->getUsers($siteSettingId );
        $branches = $this->branchService->getBranches($siteSettingId);
        $subscription = $this->subscriptionService->showSubscription($subscription->id);

        return view('admin.subscriptions.edit',get_defined_vars());
    }

    public function show(Request $request , Subscription $subscription)
    {
        $subscription = $this->subscriptionService->showSubscription($subscription->id);
        return view('admin.subscriptions.show',get_defined_vars());
    }

    public function store(AddSubscriptionRequest $request)
    {
        try {
            $this->subscriptionService->createSubscription($request->validated());
            return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating new subscription, please try again in a few minutes.');
        }
    }

    public function update(AddSubscriptionRequest $request , Subscription $subscription)
    {
        try {
            $this->subscriptionService->updateSubscription($subscription ,$request->validated());
            return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating subscription, please try again in a few minutes.');
        }
    }

    public function destroy(Subscription $subscription)
    {
        try {
            $this->subscriptionService->deleteSubscription($subscription);
            return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting subscription, please try again in a few minutes.');
        }
    }

    public function getOffers()
    {
        $siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
        $offers = $this->offerService->fetchOffers($siteSettingId );
        return response()->json(['offers' => $offers]);
    }
}
