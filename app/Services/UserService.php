<?php 
namespace App\Services;

use App\Repositories\{UserRepository, RoleRepository};
use App\Mail\UserOnboardingMail;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\{Hash, Log, Mail};
use App\Services\Auth\PasswordGenerationService;
use App\Services\{EmailService, SiteSettingService};
use App\Models\User;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected TrainerInformationService $trainerInformationService,
        protected RoleRepository $roleRepository,
        protected PasswordGenerationService $passwordGenerationService,
        protected EmailService $emailService,
        protected SiteSettingService $siteSettingService,
        protected RoleAssignmentService $roleAssignmentService
    ) {
        $this->userRepository = $userRepository;
        $this->trainerInformationService = $trainerInformationService;
        $this->roleRepository = $roleRepository;
        $this->passwordGenerationService = $passwordGenerationService;
        $this->emailService = $emailService;
        $this->siteSettingService = $siteSettingService;
        $this->roleAssignmentService = $roleAssignmentService;
    }

    public function getUsers(int $siteSettingId, $perPage = 15, $search = null)
    {
        return $this->userRepository->getAllUsers($siteSettingId, $perPage,$search);
    }

    public function getTrainers(int $siteSettingId, $perPage = 15, $branchId = null, $search = null)
    {
        return $this->userRepository->getAllTrainers($siteSettingId, $perPage, $branchId, $search);
    }

    public function getStaff(int $siteSettingId, $branchId = null)
    {
        return $this->userRepository->getAllStaff($siteSettingId,  $branchId);
    }

    public function showUser($user, array $with = [])
    {
        return $this->userRepository->findById($user->id, $with);
    }

    public function createUser(array $data, int $siteSettingId)
    {
        $image = $data['image'] ?? null;
        
        $roleIds = $data['role_ids'] ?? [];

        unset($data['image'], $data['role_ids'], $data['password']);
    
        $data['is_admin'] = 0;
        
        $user = $this->userRepository->createUser($data);
        $user->gyms()->attach($siteSettingId);
    
        $this->roleAssignmentService->assignRolesToUser($user, $roleIds);

        if ($image) {
            $user->addMedia($image)->toMediaCollection('user_images');
        }

        $gym = $this->siteSettingService->getSiteSettingById($siteSettingId);
        
        if ($gym) {
            $this->emailService->sendWelcomeEmail($user, $gym);
        }
    
        return $user;
    }

    /**
     * Create an admin-created user with password setup flow
     */
    public function createAdminUser(array $data, int $siteSettingId)
    {
        $image = $data['image'] ?? null;
        
        $trainerData = $this->extractTrainerData($data);
        $roleIds = $data['role_ids'] ?? [];

        unset($data['image'], $data['role_ids'], $data['password']);
    
        $data['password'] = null;
        $data['password_set_at'] = null;
        $data['is_admin'] = 0;
        
        $user = $this->userRepository->createUser($data);
        $user->gyms()->attach($siteSettingId);
    
        // Assign roles based on the role_ids from the request
        if (!empty($roleIds)) {
            Log::info('Assigning roles to user', ['user_id' => $user->id, 'role_ids' => $roleIds]);
            $this->roleAssignmentService->assignRolesToUser($user, $roleIds);
        }

        if ($image) {
            $user->addMedia($image)->toMediaCollection('user_images');
        }

        if ($user->hasRole('trainer') && !empty($trainerData)) {
            $this->trainerInformationService->createOrUpdateTrainerInformation($user->id, $trainerData);
        }

        $this->sendOnboardingEmail($user, $siteSettingId);
    
        return $user;
    }

    public function updateUser($user, array $data, int $siteSettingId)
    {
        $image = $data['image'] ?? null;
        $trainerData = $this->extractTrainerData($data);
        $roleIds = $data['role_ids'] ?? [];
        
        unset($data['image'], $data['role_ids']);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $updatedUser = $this->userRepository->updateUser($user, $data);
        $updatedUser->gyms()->syncWithoutDetaching([$siteSettingId]);

        $this->roleAssignmentService->assignRolesToUser($updatedUser, $roleIds);

        if ($image) {
            $updatedUser->clearMediaCollection('user_images');
            $updatedUser->addMedia($image)->toMediaCollection('user_images');
        }

        // Update trainer information if user has trainer role
        if ($updatedUser->hasRole('trainer') && !empty($trainerData)) {
            $this->trainerInformationService->createOrUpdateTrainerInformation($user->id, $trainerData);
        }

        return $updatedUser;
    }

    public function deleteUser($user, SiteSetting $siteSetting)
    {
        if ($siteSetting) {
            $this->emailService->sendAccountDeletionEmail($user, $siteSetting);
        }
        
        $this->userRepository->deleteUser($user);
        
        return $siteSetting;
    }


    /**
     * Send onboarding email to new user for password setup
     */
    public function sendOnboardingEmail($user, int $siteSettingId): void
    {
        try {
            // Only send onboarding email if user hasn't set their password yet
            if (!$user->hasSetPassword()) {
                $gymName = $user->gyms()->where('site_setting_id', $siteSettingId)->first()->gym_name;
                
                Mail::to($user->email)->send(new UserOnboardingMail($user, $gymName));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send onboarding email to user: ' . $user->email, [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
        }
    }

    /**
     * Mark user's password as set
     */
    public function markPasswordAsSet(User $user): void
    {
        $this->userRepository->updateUser($user, [
            'password_set_at' => now()
        ]);
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
}