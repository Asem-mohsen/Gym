<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use App\Services\GymContextService;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(
        private UserService $userService,
        private GymContextService $gymContextService
    ) {
        $this->userService = $userService;
        $this->gymContextService = $gymContextService;
    }

    public function index()
    {
        $gymContext = $this->gymContextService->getCurrentGymContext();
        
        return view('auth.register', compact('gymContext'));
    }

    public function register(RegisterRequest $request)
    {
        $gymContext = $this->gymContextService->getCurrentGymContext();
        
        if (!$gymContext) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'No gym context found. Please visit a gym page first.',
                    'error' => 'gym_context_missing'
                ], 400);
            }
            return redirect()->back()->withErrors(['gym' => 'No gym context found. Please visit a gym page first.']);
        }

        try {
            $data = $request->validated();
            $data['site_setting_id'] = $gymContext['id'];
            
            $user = $this->userService->createUser($data, $gymContext['id']);

            Auth::login($user);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Account created successfully!',
                    'redirect' => route('user.home', ['siteSetting' => $gymContext['slug']])
                ]);
            }

            return redirect()->route('user.home', ['siteSetting' => $gymContext['slug']])->with('success', 'Account created successfully!');
            
        } catch (\Exception $e) {
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
