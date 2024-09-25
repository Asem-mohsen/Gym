<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\Users\UpdateUser;
use App\Http\Requests\Users\AddUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponse;

    // works
    // return all gym member users for the admin panel
    public function index()
    {
        $users = User::where('isAdmin' , '0')->where('roleId', '2')->get();

        return $this->data(compact('users') , 'all users retrieved successfully' , 200);
    }

    // works
    // return all trainers for tha admin panel
    public function trainers()
    {
        $trainers = User::where('isAdmin' , '0')->where('roleId', '3')->get();

        return $this->data(compact('trainers') , 'all trainers retrieved successfully' , 200);
    }

    // works
    // return the user for the admin to edit
    public function editByAdmin(Request $request , User $user)
    {
        $user->load('roles');

        return $this->data(compact('user') , 'user data retrieved successfully' , 200);
    }

    // works
    // return the user for the user to edit
    public function edit(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        return $this->data(compact('user') , 'user data retrieved successfully' , 200);
    }

    // works
    // return the user profile with any booked
    public function profile(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $user->load('bookings.bookable');

        return $this->data(compact('user') , 'user data retrieved successfully' , 200);
    }

    // works
    // method for admins to create users manullay
    public function addUsers(AddUserRequest $request)
    {
        $data = $request->except('_method', 'token');

        $data['isAdmin'] = 0 ;

        $user = User::create($data);

        return $this->data(compact('user') , $user->name . ' created successfully' , 200);
    }

    // works
    public function update(UpdateUser $request)
    {
        $data = $request->except('_method', 'token');

        $user = Auth::guard('sanctum')->user();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        User::where('id' , $user->id)->update($data);

        $updated_user = User::where('id' , $user->id)->first();

        return $this->data(compact('updated_user') , 'user updated the data successfully' , 200);
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $user->tokens()->delete();

        $user->delete();

        return $this->success('user deleted successfully');
    }
}
