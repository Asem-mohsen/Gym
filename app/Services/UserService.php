<?php 
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function getTrainers()
    {
        return $this->userRepository->getAllTrainers();
    }

    public function showUser($user)
    {
        return $this->userRepository->findById($user->id);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = 0 ;
        return $this->userRepository->createUser($data);
    }

    public function updateUser($user, array $data)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->userRepository->updateUser($user, $data);
    }

    public function deleteUser($user)
    {
        return $this->userRepository->deleteUser($user);
    }
}