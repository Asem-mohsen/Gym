<?php 
namespace App\Services\Auth;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class LogoutService
{
    /**
     * Logout from the current session (Revoke current token for API or logout web session).
     */
    public function logoutFromCurrentSession(?Request $request = null): void
    {
        if ($this->isApiRequest($request)) {
            // Handle API token logout
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                try {
                    // Get the current token from the request
                    $token = $request ? $request->bearerToken() : null;
                    if ($token) {
                        // Find and delete the token
                        $personalAccessToken = PersonalAccessToken::findToken($token);
                        if ($personalAccessToken) {
                            $personalAccessToken->delete();
                        }
                    }
                } catch (Exception $e) {
                    // Token deletion failed, continue with logout
                }
            }
        } else {
            // Handle web session logout
            Auth::logout();
            if ($request && $request->session()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }
    }

    /**
     * Logout from all sessions (Revoke all tokens for API or logout all web sessions).
     */
    public function logoutFromAllSessions(?Request $request = null): void
    {
        if ($this->isApiRequest($request)) {
            // Handle API tokens logout
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                try {
                    // Delete all tokens for the user using the database directly
                    $userId = $user->id;
                    PersonalAccessToken::where('tokenable_id', $userId)
                        ->where('tokenable_type', get_class($user))
                        ->delete();
                } catch (Exception $e) {
                    // Token deletion failed, continue with logout
                }
            }
        } else {
            // Handle web session logout
            if ($request && $request->input('password')) {
                Auth::logoutOtherDevices($request->input('password'));
            }
            Auth::logout();
            if ($request && $request->session()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }
    }

    /**
     * Logout from all other sessions except the current one.
     */
    public function logoutFromOtherSessions(?Request $request = null): void
    {
        if ($this->isApiRequest($request)) {
            // Handle API tokens logout
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                try {
                    $currentToken = $request ? $request->bearerToken() : null;
                    if ($currentToken) {
                        $personalAccessToken = PersonalAccessToken::findToken($currentToken);
                        if ($personalAccessToken) {
                            $currentTokenId = $personalAccessToken->id;
                            $userId = $user->id;
                            // Delete all other tokens except the current one
                            PersonalAccessToken::where('tokenable_id', $userId)
                                ->where('tokenable_type', get_class($user))
                                ->where('id', '!=', $currentTokenId)
                                ->delete();
                        }
                    }
                } catch (Exception $e) {
                    // Token deletion failed, continue with logout
                }
            }
        } else {
            // Handle web session logout
            if ($request && $request->input('password')) {
                Auth::logoutOtherDevices($request->input('password'));
            }
        }
    }

    /**
     * Check if the request is an API request
     */
    private function isApiRequest(?Request $request): bool
    {
        if (!$request) {
            // If no request is passed, check the current guard
            return Auth::getDefaultDriver() === 'sanctum';
        }
        return $request->expectsJson() || $request->is('api/*');
    }
}