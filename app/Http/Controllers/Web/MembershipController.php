<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membership\{ AddMembershipRequest , UpdateMembershipRequest};
use App\Models\Membership;
use App\Services\MembershipService;
use Exception;

class MembershipController extends Controller
{
    public function __construct(protected MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function index()
    {
        $memberships = $this->membershipService->getMemberships(withCount: ['bookings']);
        $maxBookings = $memberships->max('bookings_count');
        return view('admin.memberships.index', get_defined_vars());
    }

    public function create()
    {
        return view('admin.memberships.create');
    }

    public function store(AddMembershipRequest $request)
    {
        try {
            $this->membershipService->createMembership($request->validated());
            return redirect()->route('membership.index')->with('success', 'Membership created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while adding a new membership, please try again in a few minutes.');
        }
    }

    public function show(Membership $membership)
    {
        $membership = $this->membershipService->showMembership($membership);

        return view('admin.memberships.show', get_defined_vars());
    }

    public function edit(Membership $membership)
    {
        $membership = $this->membershipService->showMembership($membership);
        return view('admin.memberships.edit', get_defined_vars());
    }

    public function update(UpdateMembershipRequest $request , Membership $membership)
    {
        try {
            $this->membershipService->updateMembership($membership, $request->validated());
            return redirect()->route('membership.index')->with('success', 'Membership updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while updating the membership, please try again in a few minutes.');
        }
    }

    public function destroy(Membership $membership)
    {
        try {
            $this->membershipService->deleteMembership($membership);
            return redirect()->route('membership.index')->with('success', 'Membership deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while deleting the membership, please try again in a few minutes.');
        }
    }
}
