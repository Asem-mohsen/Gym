<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Models\SiteSetting;

class TeamController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $trainers = $this->userService->getTrainers(siteSettingId: $siteSetting->id);
        return view('user.team', compact('trainers'));
    }
}
