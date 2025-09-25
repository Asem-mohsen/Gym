<?php 
namespace App\Services\Auth;

use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Models\SiteSetting;
use App\Services\UserService;
use Illuminate\Support\Facades\{DB, Hash, Mail};
use Illuminate\Support\{Carbon, Str};

class ForgetPasswordService
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function sendResetToken(string $email, string $type = 'token', ?SiteSetting $gym = null): bool
    {
        $user = $this->userService->findUserBy('email', $email);

        if (!$user) return false;

        if ($type === 'token') {
            $token = Str::random(64);
        } else {
            $token = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        }

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        Mail::to($email)->send(new PasswordResetMail($token, $user, $gym));

        return true;
    }


    public function verifyTokenOrCode(string $tokenOrCode, string $email, string $type = 'token', ?SiteSetting $gym = null)
    {
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) return false;

        $isExpired = Carbon::parse($record->created_at)->addMinutes(config('auth.passwords.users.expire', 60))->isPast();

        if ($isExpired) {
            $this->sendResetToken($email, $type, $gym);
            return 'expired';
        }

        return Hash::check($tokenOrCode, $record->token);
    }

    public function resetPassword(string $email, ?string $token = null, string $newPassword, ?SiteSetting $gym = null): string
    {
        if ($token) {
            $valid = $this->verifyTokenOrCode(tokenOrCode: $token, email: $email, gym: $gym);

            if ($valid === 'expired') {
                return 'expired';
            }
    
            if (!$valid) {
                return 'invalid';
            }
        }

        $user = User::where('email', $email)->first();
        $user->update(['password' => Hash::make($newPassword)]);

        $this->userService->markPasswordAsSet($user);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return 'success';
    }

}