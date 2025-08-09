<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\UserService;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __construct(private UserService $userService) {
        $this->userService = $userService;
    }
    public function aboutUs(SiteSetting $siteSetting)
    {
        $trainers = $this->userService->getTrainers($siteSetting->id);
        return view('user.about-us', compact('trainers'));
    }
}
