<?php 
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsers(int $siteSettingId)
    {
        return $this->userRepository->getAllUsers($siteSettingId);
    }

    public function getTrainers(int $siteSettingId)
    {
        return $this->userRepository->getAllTrainers($siteSettingId);
    }

    public function showUser($user)
    {
        return $this->userRepository->findById($user->id);
    }

    public function createUser(array $data , int $siteSettingId)
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = 0 ;

        $user = $this->userRepository->createUser($data);
        $user->gyms()->attach($siteSettingId); 

        return $user;
    }

    public function updateUser($user, array $data, int $siteSettingId)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        $updatedUser = $this->userRepository->updateUser($user, $data);

        $updatedUser->gyms()->syncWithoutDetaching([$siteSettingId]);

        return $updatedUser;
    }

    public function deleteUser($user)
    {
        return $this->userRepository->deleteUser($user);
    }
}