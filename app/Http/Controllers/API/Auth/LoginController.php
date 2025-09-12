<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;

class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $data = $this->authService->login($credentials);

            return successResponse($data, 'User logged in successfully');
        } catch (Exception $e) {
            return failureResponse($e->getMessage(), $e->getCode());
        }
    }
}
