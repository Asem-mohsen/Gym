<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membership\{AddMembershipRequest,UpdateMembershipRequest};
use App\Models\Membership;
use App\Services\MembershipService;
use Exception;

class MembershipController extends Controller
{
    protected $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function index()
    {
        try {
            $memberships = $this->membershipService->getMemberships();
            return successResponse(compact('memberships'), 'Memberships data retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving memberships, please try again.');
        }
    }

    public function store(AddMembershipRequest $request)
    {
        try {
            $membership = $this->membershipService->createMembership($request->validated());
            return successResponse(compact('membership'), 'Membership data successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating membership, please try again.');
        }
    }

    public function show(Membership $membership)
    {
        try {
            $membership = $this->membershipService->showMembership($membership);
            return successResponse(compact('membership'), $membership->name .' data successfully');
        } catch (Exception $e) {
            return failureResponse('Error fetching membership, please try again.');
        }
    }

    public function edit(Membership $membership)
    {
        try {
            $membership = $this->membershipService->showMembership($membership);
            return successResponse(compact('membership'), $membership->name .' data successfully');
        } catch (Exception $e) {
            return failureResponse('Error fetching membership, please try again.');
        }
    }

    public function update(UpdateMembershipRequest $request , Membership $membership)
    {
        try {
            $updatedMembership = $this->membershipService->updateMembership($membership, $request->validated());
            return successResponse(compact('updatedMembership'), 'Membership updated successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while updating membership, please try again in a few minutes');
        }
    }

    public function destroy(Membership $membership)
    {
        try {
            $this->membershipService->deleteMembership($membership);
            return successResponse(message: 'Membership deleted successfully');
        } catch (Exception $e) {
            return failureResponse('Error happened while deleting membership, please try again in a few minutes');
        }
    }
}
