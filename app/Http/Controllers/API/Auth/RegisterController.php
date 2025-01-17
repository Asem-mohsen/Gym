<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;

class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->only(['name', 'email', 'password']);
            $response = $this->authService->register($data);

            return successResponse($response, 'User registered successfully');
        } catch (\Exception $e) {
            return failureResponse($e->getMessage(), 400);
        }
    }
}
