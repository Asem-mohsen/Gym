<?php

namespace App\Services\OnBoarding;

use Exception;
use App\Mail\AdminOnboardingMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminOnboardingService
{
    public function sendOnboardingEmail(User $user): bool
    {
        try {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            $gym = $user->gyms()->first();
            $gymName = $gym->gym_name ?? 'Our Gym';
            $gymSlug = $gym->slug ?? 'our-gym';
            $gymContactEmail = $gym->contact_email ?? null;
            Mail::to($user->email)->send(new AdminOnboardingMail($user, $gymName, $gymSlug, $token, $gymContactEmail));

            return true;
        } catch (Exception $e) {
            Log::error('Failed to send admin onboarding email: ' . $e->getMessage());
            return false;
        }
    }

    public function resendOnboardingEmail(User $user): bool
    {
        return $this->sendOnboardingEmail($user);
    }

    public function verifyOnboardingToken(string $token, string $email): bool
    {
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return false;
        }

        return Hash::check($token, $record->token);
    }

    public function completeOnboarding(string $email, string $token, string $newPassword): string
    {
        if (!$this->verifyOnboardingToken($token, $email)) {
            return 'invalid';
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return 'user_not_found';
        }

        $user->update(['password' => Hash::make($newPassword) , 'password_set_at' => now()]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return 'success';
    }
}
