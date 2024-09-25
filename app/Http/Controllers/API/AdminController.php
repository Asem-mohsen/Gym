<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\Admins\AddRequest;
use App\Http\Requests\Admins\UpdateRequest;
use App\Models\User;
use App\Models\Role;

class AdminController extends Controller
{
    use ApiResponse;

    // works
    public function index()
    {
        $admins = User::where('isAdmin', '1')->get();

        return $this->data(compact('admins') , 'admins data retrieved successfully' , 200);
    }

    // works
    public function create()
    {
        $roles = Role::all();

        return $this->data(compact('roles') , 'roles for adding admins retrieved successfully' , 200);
    }

    // works
    public function store(AddRequest $request)
    {
        $data = $request->except('_method' , 'token');

        $data['password'] = bcrypt($request->password);

        $newAdmin = User::create($data);

        return $this->data(compact('newAdmin') , 'Admin added successfully' , 200);
    }

    //works
    public function update(UpdateRequest $request , User $user)
    {
        $data = $request->except('_method' , 'token');

        $data['password'] = bcrypt($request->password);

        User::where('id' , $user->id)->update($data);

        return $this->success('Admin updated successfully' , 200);
    }

    // works
    public function edit(User $user)
    {
        $roles = Role::all();

        return $this->data(compact('user' , 'roles') , 'Admin retrieved successfully' , 200);
    }

    // works
    public function destroy(Request $request , User $user)
    {
        User::where('id' ,  $user->id)->delete();

        return $this->success('Admin deleted successfully');
    }
}
