<?php 
namespace App\Services;

use App\Models\Role;
use App\Repositories\{UserRepository, RoleRepository};
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected TrainerInformationService $trainerInformationService,
        protected RoleRepository $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->trainerInformationService = $trainerInformationService;
        $this->roleRepository = $roleRepository;
    }

    public function getUsers(int $siteSettingId)
    {
        return $this->userRepository->getAllUsers($siteSettingId);
    }

    public function getTrainers(int $siteSettingId)
    {
        return $this->userRepository->getAllTrainers($siteSettingId);
    }

    public function showUser($user, array $with = [])
    {
        return $this->userRepository->findById($user->id, $with);
    }

    public function createUser(array $data, int $siteSettingId)
    {
        $role = $this->roleRepository->getRoleByName('System User', $siteSettingId);

        if (!$role) {
            $role = Role::firstOrCreate(
                [
                    'name' => 'System User',
                    'site_setting_id' => $siteSettingId
                ],
                [
                    'description' => 'Default system user role',
                ]
            );
        }
    
        $image = $data['image'] ?? null;
        
        $trainerData = $this->extractTrainerData($data);

        unset($data['image']);
    
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = 0;
        $data['role_id'] = $role->id;
    
        $user = $this->userRepository->createUser($data);
        $user->gyms()->attach($siteSettingId);
    
        if ($image) {
            $user->addMedia($image)->toMediaCollection('user_images');
        }

        if ($this->isTrainerRole($data['role_id']) && !empty($trainerData)) {
            $this->trainerInformationService->createOrUpdateTrainerInformation($user->id, $trainerData);
        }
    
        return $user;
    }

    public function updateUser($user, array $data, int $siteSettingId)
    {
        $image = $data['image'] ?? null;
        $trainerData = $this->extractTrainerData($data);
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

        if ($this->isTrainerRole($data['role_id'] ?? $user->role_id) && !empty($trainerData)) {
            $this->trainerInformationService->createOrUpdateTrainerInformation($user->id, $trainerData);
        }

        return $updatedUser;
    }

    public function deleteUser($user)
    {
        return $this->userRepository->deleteUser($user);
    }

    /**
     * Extract trainer-specific data from the request data
     */
    private function extractTrainerData(array $data): array
    {
        $trainerFields = [
            'weight', 'height', 'date_of_birth', 'brief_description',
            'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url'
        ];

        $trainerData = [];
        foreach ($trainerFields as $field) {
            if (isset($data[$field])) {
                $trainerData[$field] = $data[$field];
            }
        }

        return $trainerData;
    }

    /**
     * Check if the role is trainer
     */
    private function isTrainerRole(int $roleId): bool
    {
        $role = Role::find($roleId);
        return $role && strtolower($role->name) === 'trainer';
    }
}