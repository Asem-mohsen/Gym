<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(SiteSetting $gym)
    {
        $data = [
            'gym' => $gym,
        ];

        return successResponse($data, 'Dashboard data retrieved successfully');
    }
}
