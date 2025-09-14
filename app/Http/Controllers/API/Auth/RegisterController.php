<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\SiteSetting;
use App\Services\Auth\AuthService;

class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request, SiteSetting $gym)
    {
        try {
            $data = $request->only(['name', 'email', 'password']);
            $data['site_setting_id'] = $gym->id;
            $response = $this->authService->register($data, $gym);

            return successResponse($response, 'User registered successfully');
        } catch (Exception $e) {
            return failureResponse($e->getMessage(), 400);
        }
    }
}
