<?php 
namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(protected UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle user login and return token if successful.
     */
    public function login(array $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $accessToken = $this->generateToken($user);

            return [
                'user' => $user,
                'token' => $accessToken,
            ];
        }

        throw new \Exception('Authentication failed', 401);
    }

    /**
     * Register a new user and generate a token.
     */
    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = $data['role_id'] ?? 2;

        $user = $this->userRepository->createUser($data);

        $accessToken = $this->generateToken($user);

        return [
            'user' => $user,
            'token' => $accessToken,
        ];
    }

    /**
     * Generate a personal access token for a user.
     */
    private function generateToken(User $user): string
    {
        return $user->createToken('API Token')->plainTextToken;
    }

    /**
    * Handle user login for web sessions.
    */
   public function webLoign(array $credentials): void
   {
       if (!Auth::attempt($credentials)) {
           throw new \Exception('Invalid credentials provided.', 401);
       }

       $user = Auth::user();

       if (!$user->status) {
           Auth::logout();
           throw new \Exception('Your account has been disabled.', 403);
       }
   }

   public function handleUnauthorizedAccess(User $user): void
    {
        $user->update(['status' => false]);

        Auth::logout();
    }
}