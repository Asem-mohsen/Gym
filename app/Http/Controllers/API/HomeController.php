<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\User;
use App\Models\Membership;

class HomeController extends Controller
{
    use ApiResponse ;

    // works
    public function index()
    {
        $memberships = Membership::all();
        $users = User::where('isAdmin' , 3)->get(); //trainers

        $data = [
            'memberships'=>$memberships,
            'trainers'   =>$users
        ];

        return $this->data($data, 'Home data retrieved' , 200);
    }
}
