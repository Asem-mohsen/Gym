<?php 
namespace App\Services;

use App\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;

class AdminService
{

    public function __construct(protected AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function getAdmins(int $siteSettingId)
    {
        return $this->adminRepository->getAllAdmins($siteSettingId);
    }

    public function createAdmin(array $data, int $siteSettingId)
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = 1;

        $user = $this->adminRepository->createAdmin($data);
        $user->gyms()->attach($siteSettingId);

        return $user;
    }

    public function updateAdmin($user, array $data , int $siteSettingId)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $updatedUser = $this->adminRepository->updateAdmin($user, $data);

        $updatedUser->gyms()->syncWithoutDetaching([$siteSettingId]);

        return $updatedUser;
    }

    public function deleteAdmin($user)
    {
        return $this->adminRepository->deleteAdmin($user);
    }
}