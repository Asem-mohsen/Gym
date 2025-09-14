<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\{MembershipResource, TrainerResource, BranchResource};
use App\Models\{Membership, SiteSetting};
use App\Services\{MembershipService, UserService, SubscriptionService, BranchService};
use Exception;
use Illuminate\Support\Facades\{Log, Auth};

class MembershipController extends Controller
{
    public function __construct(protected MembershipService $membershipService, protected UserService $userService, protected SubscriptionService $subscriptionService, protected BranchService $branchService)
    {
        $this->membershipService = $membershipService;
        $this->userService = $userService;
        $this->subscriptionService = $subscriptionService;
        $this->branchService = $branchService;
    }

    public function index(SiteSetting $gym)
    {
        try {
            $memberships = $this->membershipService->getMemberships($gym->id);
            return successResponse(MembershipResource::collection($memberships), 'Memberships data retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error fetching memberships: ' . $e->getMessage());
            return failureResponse('Error retrieving memberships, please try again.');
        }
    }

    public function show(SiteSetting $gym, Membership $membership)
    {
        try {
            if ($membership->site_setting_id != $gym->id) {
                return failureResponse('Invalid membership or gym', 400);
            }

            $trainers = $this->userService->getTrainers(siteSettingId: $gym->id);
            $membership = $this->membershipService->showMembership($membership);
            $branches = $this->branchService->getBranchesForPublic($gym->id);

            $userSubscription = null;
            if (Auth::guard('sanctum')->check()) {
                $userSubscription = $this->subscriptionService->getActiveSubscription(
                    userId: Auth::guard('sanctum')->user()->id,
                    siteSettingId: $gym->id
                );
            }
            
            $data = [
                'membership' => new MembershipResource($membership),
                'trainers' => TrainerResource::collection($trainers),
                'branches' => BranchResource::collection($branches),
                'user_subscription' => $userSubscription,
            ];

            return successResponse($data, $membership->name .' retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error fetching membership: ' . $e->getMessage());
            return failureResponse('Error fetching membership, please try again.');
        }
    }
}
