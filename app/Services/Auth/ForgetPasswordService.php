<?php 
namespace App\Services\Auth;

use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\{DB, Hash, Mail};
use Illuminate\Support\{Carbon, Str};

class ForgetPasswordService
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function sendResetToken(string $email): bool
    {
        $user = User::where('email', $email)->first();

        if (!$user) return false;

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        Mail::to($email)->send(new PasswordResetMail($token, $user));

        return true;
    }

    public function verifyToken(string $token, string $email)
    {
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) return false;

        $isExpired = Carbon::parse($record->created_at)->addMinutes(config('auth.passwords.users.expire', 60))->isPast();

        if ($isExpired) {
            $this->sendResetToken($email);
            return 'expired';
        }

        return Hash::check($token, $record->token);
    }

    public function resetPassword(string $email, string $token, string $newPassword): string
    {
        $valid = $this->verifyToken($token, $email);

        if ($valid === 'expired') {
            return 'expired';
        }

        if (!$valid) {
            return 'invalid';
        }

        $user = User::where('email', $email)->first();
        $user->update(['password' => Hash::make($newPassword)]);

        $this->userService->markPasswordAsSet($user);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return 'success';
    }
}