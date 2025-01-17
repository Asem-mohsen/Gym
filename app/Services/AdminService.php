<?php 
namespace App\Services;

use App\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function getAdmins()
    {
        return $this->adminRepository->getAllAdmins();
    }

    public function createAdmin(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->adminRepository->createAdmin($data);
    }

    public function updateAdmin($user, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->adminRepository->updateAdmin($user, $data);
    }

    public function deleteAdmin($user)
    {
        return $this->adminRepository->deleteAdmin($user);
    }
}