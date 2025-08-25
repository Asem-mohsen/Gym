<?php 
namespace App\Services;

use App\Models\Role;
use App\Repositories\{UserRepository, RoleRepository};
use App\Mail\UserOnboardingMail;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\{Hash, Log, Mail};
use App\Services\Auth\PasswordGenerationService;
use App\Services\{EmailService, SiteSettingService};
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected TrainerInformationService $trainerInformationService,
        protected RoleRepository $roleRepository,
        protected PasswordGenerationService $passwordGenerationService,
        protected EmailService $emailService,
        protected SiteSettingService $siteSettingService
    ) {
        $this->userRepository = $userRepository;
        $this->trainerInformationService = $trainerInformationService;
        $this->roleRepository = $roleRepository;
        $this->passwordGenerationService = $passwordGenerationService;
        $this->emailService = $emailService;
        $this->siteSettingService = $siteSettingService;
    }

    public function getUsers(int $siteSettingId, $perPage = 15, $branchId = null, $search = null)
    {
        return $this->userRepository->getAllUsers($siteSettingId, $perPage, $branchId, $search);
    }

    public function getTrainers(int $siteSettingId, $perPage = 15, $branchId = null, $search = null)
    {
        return $this->userRepository->getAllTrainers($siteSettingId, $perPage, $branchId, $search);
    }

    public function showUser($user, array $with = [])
    {
        return $this->userRepository->findById($user->id, $with);
    }

    public function createUser(array $data, int $siteSettingId)
    {
        $image = $data['image'] ?? null;
        
        $trainerData = $this->extractTrainerData($data);
        $roleIds = $data['role_ids'] ?? [];

        unset($data['image'], $data['role_ids'], $data['password']);
    
        // Generate a random password
        $temporaryPassword = $this->passwordGenerationService->generateTemporaryPassword();
        $data['password'] = Hash::make($temporaryPassword);
        $data['is_admin'] = 0;
        
        $user = $this->userRepository->createUser($data);
        $user->gyms()->attach($siteSettingId);
    
        // Assign roles
        if (!empty($roleIds)) {
            $roles = SpatieRole::whereIn('id', $roleIds)->get();
            $user->assignRole($roles);
        } else {
            // Default to regular_user role
            $regularUserRole = SpatieRole::where('name', 'regular_user')->first();
            if ($regularUserRole) {
                $user->assignRole($regularUserRole);
            }
        }

        if ($image) {
            $user->addMedia($image)->toMediaCollection('user_images');
        }

        // Check if user has trainer role and create trainer information
        if ($user->hasRole('trainer') && !empty($trainerData)) {
            $this->trainerInformationService->createOrUpdateTrainerInformation($user->id, $trainerData);
        }

        // Send welcome email
        $gym = $this->siteSettingService->getSiteSettingById($siteSettingId);
        
        if ($gym) {
            $this->emailService->sendWelcomeEmail($user, $gym);
        }
    
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

        // Update roles
        if (!empty($roleIds)) {
            $roles = SpatieRole::whereIn('id', $roleIds)->get();
            $updatedUser->syncRoles($roles);
        }

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
     * Check if user has set their password
     */
    public function hasUserSetPassword(User $user): bool
    {
        // If there's no token in password_reset_tokens table, user has set their password
        $tokenRecord = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        return !$tokenRecord;
    }


    /**
     * Send onboarding email to new user
     */
    public function sendOnboardingEmail($user, int $siteSettingId): void
    {
        try {
            $gymName = $user->gyms()->where('site_setting_id', $siteSettingId)->first()->gym_name;
            
            Mail::to($user->email)->send(new UserOnboardingMail($user, $gymName));
        } catch (\Exception $e) {
            Log::error('Failed to send onboarding email to user: ' . $user->email, [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
        }
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