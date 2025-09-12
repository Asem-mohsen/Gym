<?php

namespace App\Http\Controllers\Web\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use App\Services\{ GymContextService, GymBrandingService };
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private GymContextService $gymContextService,
        private GymBrandingService $gymBrandingService
    ) {
    }

    public function index()
    {
        $gymContext = $this->gymContextService->getCurrentGymContext();
        
        $brandingData = null;
        $gymCssVariables = null;
        
        if ($gymContext && isset($gymContext['id'])) {
            try {
                $brandingData = $this->gymBrandingService->getBrandingForAdmin($gymContext['id']);
                $gymCssVariables = $this->gymBrandingService->generateCssVariables($gymContext['id']);
            } catch (Exception $e) {
                Log::warning('Failed to load branding data for login page: ' . $e->getMessage());
            }
        }
        
        return view('auth.login', compact('gymContext', 'brandingData', 'gymCssVariables'));
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $user = $this->authService->webLoign($credentials);

            $gymContext = $this->gymContextService->getCurrentGymContext();
            
            if ($request->ajax() || $request->expectsJson()) {
                if ($gymContext && $user->hasRole('regular_user')) {
                    return response()->json([
                        'message' => 'Login successful!',
                        'redirect' => route('user.home', ['siteSetting' => $gymContext['slug']])
                    ]);
                }
                
                return response()->json([
                    'message' => 'Login successful!',
                    'redirect' => route('admin.dashboard')
                ]);
            }

            if ($gymContext && $user->hasRole('regular_user')) {
                return redirect()->route('user.home', ['siteSetting' => $gymContext['slug']]);
            }

            return redirect()->route('admin.dashboard');
            
        } catch (Exception $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'error' => 'login_failed'
                ], 401);
            }
            
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
