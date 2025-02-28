<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\AddSubscriptionRequest;
use App\Models\Subscription;
use App\Services\{ BranchService , SubscriptionService , MembershipService, OfferService, UserService};
use Exception;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService ,
        protected UserService $userService,
        protected MembershipService $membershipService,
        protected BranchService $branchService,
        protected OfferService $offerService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->userService = $userService;
        $this->membershipService = $membershipService;
        $this->branchService = $branchService;
    }

    public function index()
    {
        [$subscriptions, $counts] = $this->subscriptionService->getSubscriptions();
        return view('admin.subscriptions.index', compact('subscriptions', 'counts'));
    }

    public function create()
    {
        $memberships = $this->membershipService->getMemberships();
        $users    = $this->userService->getUsers();
        $branches = $this->branchService->getBranches();

        return view('admin.subscriptions.create',get_defined_vars());
    }

    public function edit(Request $request , Subscription $subscription)
    {
        $memberships = $this->membershipService->getMemberships();
        $users    = $this->userService->getUsers();
        $branches = $this->branchService->getBranches();

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
        $offers = $this->offerService->fetchOffers();
        return response()->json(['offers' => $offers]);
    }
}
