<?php

namespace App\Http\Controllers\Web\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\SiteSetting;
use App\Services\Auth\AuthService;
use App\Services\GymBrandingService ;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private GymBrandingService $gymBrandingService
    ) {
    }

    public function index(SiteSetting $siteSetting)
    {
        $brandingData = null;
        $gymCssVariables = null;
        
        if ($siteSetting && isset($siteSetting->id)) {
            try {
                $brandingData = $this->gymBrandingService->getBrandingForAdmin($siteSetting->id);
                $gymCssVariables = $this->gymBrandingService->generateCssVariables($siteSetting->id);
            } catch (Exception $e) {
                Log::warning('Failed to load branding data for login page: ' . $e->getMessage());
            }
        }
        
        return view('auth.login', compact('siteSetting', 'brandingData', 'gymCssVariables'));
    }

    public function login(LoginRequest $request, SiteSetting $siteSetting)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $user = $this->authService->webLoign($credentials);

            if ($request->ajax() || $request->expectsJson()) {
                if ($siteSetting && $user->hasRole('regular_user')) {
                    return response()->json([
                        'message' => 'Login successful!',
                        'redirect' => route('user.home', ['siteSetting' => $siteSetting->slug])
                    ]);
                }
                
                return response()->json([
                    'message' => 'Login successful!',
                    'redirect' => route('admin.dashboard')
                ]);
            }

            if ($siteSetting && $user->hasRole('regular_user')) {
                return redirect()->route('user.home', ['siteSetting' => $siteSetting->slug]);
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
