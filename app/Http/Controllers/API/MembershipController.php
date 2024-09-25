<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Membership\AddRequest;
use App\Http\Requests\Membership\UpdateRequest;
use App\Traits\ApiResponse;
use App\Models\User;
use App\Models\Membership;

class MembershipController extends Controller
{
    use ApiResponse;

    // works
    public function index()
    {
        $memberships = Membership::where('status' , 1)->get();

        return $this->data(compact('memberships'), 'Memberships retrieved' , 200);
    }

    // works
    public function store(AddRequest $request)
    {
        $data = $request->except('_method' , 'token');

        $memberships = Membership::create($data);

        return $this->data(compact('memberships'), 'Memberships added successfully' , 200);
    }

    // works
    public function show(Membership $membership)
    {
        return $this->data(compact('membership'), $membership->name . ' membership retrieved' , 200);
    }

    // works
    public function edit(Membership $membership)
    {
        return $this->data(compact('membership'), $membership->name . ' membership retrieved' , 200);
    }

    // works
    public function update(UpdateRequest $request , Membership $membership)
    {
        $data = $request->except('_method' , 'token');

        Membership::where('id' ,  $membership->id)->update($data);

        return $this->success('Memberships updated successfully');
    }

    // works
    public function destroy(Request $request , Membership $membership)
    {
        Membership::where('id' ,  $membership->id)->delete();

        return $this->success('Membership deleted successfully');
    }
}
