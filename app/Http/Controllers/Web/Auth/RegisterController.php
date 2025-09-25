<?php

namespace App\Http\Controllers\Web\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\SiteSetting;
use App\Services\{ UserService, GymBrandingService };
use Illuminate\Support\Facades\{Auth, Log};

class RegisterController extends Controller
{
    public function __construct(
        private UserService $userService,
        private GymBrandingService $gymBrandingService
    ) {
        $this->userService = $userService;
    }

    public function index(SiteSetting $siteSetting)
    {
        // Get branding data for register page
        $brandingData = null;
        $gymCssVariables = null;
        
        if ($siteSetting && isset($siteSetting->id)) {
            try {
                $brandingData = $this->gymBrandingService->getBrandingForAdmin($siteSetting->id);
                $gymCssVariables = $this->gymBrandingService->generateCssVariables($siteSetting->id);
            } catch (Exception $e) {
                Log::warning('Failed to load branding data for register page: ' . $e->getMessage());
            }
        }
        
        return view('auth.register', compact('siteSetting', 'brandingData', 'gymCssVariables'));
    }

    public function register(RegisterRequest $request, SiteSetting $siteSetting)
    {
        if (!$siteSetting) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'No gym context found. Please visit a gym page first.',
                    'error' => 'gym_context_missing'
                ], 400);
            }
            return redirect()->back()->withErrors(['gym' => 'No gym found. Please visit a gym page first.']);
        }

        try {
            $data = $request->validated();
            $data['site_setting_id'] = $siteSetting->id;
            
            $user = $this->userService->createUser($data, $siteSetting);

            Auth::login($user);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Account created successfully!',
                    'redirect' => route('user.home', ['siteSetting' => $siteSetting->slug])
                ]);
            }

            return redirect()->route('user.home', ['siteSetting' => $siteSetting->slug])->with('success', 'Account created successfully!');
            
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'An error occurred while creating your account. Please try again.',
                    'error' => 'registration_failed'
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating your account. Please try again.']);
        }
    }
}
