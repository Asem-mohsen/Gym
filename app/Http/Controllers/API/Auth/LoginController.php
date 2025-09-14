<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        } catch (UnauthorizedHttpException $e) {
            return failureResponse($e->getMessage(), 403); // or 401 if you prefer
        } catch (\Throwable $e) {
            return failureResponse($e->getMessage(), 500);
        }
    }
}
