<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\MembershipService;
use App\Services\UserService;

class HomeController extends Controller
{
    protected $membershipService , $userService;

    public function __construct(MembershipService $membershipService , UserService $userService)
    {
        $this->membershipService = $membershipService;
        $this->userService = $userService;
    }
    
    public function index()
    {
        $memberships = $this->membershipService->getMemberships();
        $trainers    = $this->userService->getTrainers();

        $data = [
            'memberships'=>$memberships,
            'trainers'   =>$trainers
        ];

        return successResponse($data, 'Home data retrieved');
    }
}
