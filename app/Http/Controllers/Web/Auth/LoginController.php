<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use App\Services\GymContextService;

class LoginController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private GymContextService $gymContextService
    ) {
    }

    public function index()
    {
        $gymContext = $this->gymContextService->getCurrentGymContext();
        return view('auth.login', compact('gymContext'));
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $this->authService->webLoign($credentials);

            $gymContext = $this->gymContextService->getCurrentGymContext();
            if ($gymContext) {
                return redirect()->route('user.home', ['siteSetting' => $gymContext['slug']]);
            }

            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
