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
    protected int $siteSettingId;
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
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index(Request $request)
    {
        $siteSettingId = $this->siteSettingId;
        $branchId = $request->get('branch_id');
        $status = $request->get('status');
        $membershipId = $request->get('membership_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $subscriptions = $this->subscriptionService->getSubscriptions(
            $siteSettingId, 
            $branchId, 
            $status, 
            $membershipId, 
            $dateFrom, 
            $dateTo
        );
        
        $branches = $this->branchService->getBranches($siteSettingId);
        $memberships = $this->membershipService->getMemberships($siteSettingId);
        
        return view('admin.subscriptions.index', compact('subscriptions', 'branches', 'memberships'));
    }

    public function create()
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $this->siteSettingId);
        $users    = $this->userService->getUsers($this->siteSettingId );
        $branches = $this->branchService->getBranches($this->siteSettingId);

        return view('admin.subscriptions.create',get_defined_vars());
    }

    public function edit(Request $request , Subscription $subscription)
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $this->siteSettingId);
        $users    = $this->userService->getUsers($this->siteSettingId );
        $branches = $this->branchService->getBranches($this->siteSettingId);
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
        $offers = $this->offerService->fetchOffers($this->siteSettingId);
        return response()->json(['offers' => $offers]);
    }
}
