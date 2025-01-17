<?php 
namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
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

}