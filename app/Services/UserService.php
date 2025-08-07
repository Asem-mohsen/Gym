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

    public function createUser(array $data, int $siteSettingId)
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = 0;

        $user = $this->userRepository->createUser($data);
        $user->gyms()->attach($siteSettingId);

        if ($image) {
            $user->addMedia($image)->toMediaCollection('user_images');
        }

        return $user;
    }

    public function updateUser($user, array $data, int $siteSettingId)
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $updatedUser = $this->userRepository->updateUser($user, $data);
        $updatedUser->gyms()->syncWithoutDetaching([$siteSettingId]);

        if ($image) {
            $updatedUser->clearMediaCollection('user_images');
            $updatedUser->addMedia($image)->toMediaCollection('user_images');
        }

        return $updatedUser;
    }

    public function deleteUser($user)
    {
        return $this->userRepository->deleteUser($user);
    }
}