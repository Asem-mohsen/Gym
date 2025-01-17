<?php 
namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LogoutService
{
     /**
     * Logout from the current session (Revoke current token).
     */
    public function logoutFromCurrentSession(): void
    {
        $token = Auth::user()->currentAccessToken();
        if ($token) {
            $token->delete();
        }
    }

    /**
     * Logout from all sessions (Revoke all tokens for the user).
     */
    public function logoutFromAllSessions(): void
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }
    }

    /**
     * Logout from all other sessions except the current one.
     */
    public function logoutFromOtherSessions(): void
    {
        $user = Auth::user();
        $currentTokenId = Auth::user()->currentAccessToken()->id;

        if ($user) {
            $user->tokens()->where('id', '!=', $currentTokenId)->delete();
        }
    }
}