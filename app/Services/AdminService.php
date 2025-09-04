<?php 
namespace App\Services;

use App\Repositories\AdminRepository;
use App\Mail\AdminOnboardingMail;
use Illuminate\Support\Facades\{Hash, Log, Mail, DB};
use App\Services\Auth\PasswordGenerationService;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\User;

class AdminService
{

    public function __construct(
        protected AdminRepository $adminRepository,
        protected PasswordGenerationService $passwordGenerationService,
        protected RoleAssignmentService $roleAssignmentService
    ) {
        $this->adminRepository = $adminRepository;
        $this->passwordGenerationService = $passwordGenerationService;
        $this->roleAssignmentService = $roleAssignmentService;
    }

    public function getAdmins(int $siteSettingId, $perPage = 15, $search = null)
    {
        return $this->adminRepository->getAllAdmins($siteSettingId, $perPage, $search);
    }

    public function createAdmin(array $data, int $siteSettingId)
    {
        $roleIds = $data['role_ids'] ?? [];
        unset($data['role_ids'], $data['password']);
        
        // Generate a random password
        $temporaryPassword = $this->passwordGenerationService->generateTemporaryPassword();
        $data['password'] = Hash::make($temporaryPassword);
        $data['is_admin'] = 1;

        $user = $this->adminRepository->createAdmin($data);
        $user->gyms()->attach($siteSettingId);

        // Assign roles using the role assignment service
        $this->roleAssignmentService->assignRolesToUser($user, $roleIds);

        $this->sendOnboardingEmail($user, $siteSettingId);

        return $user;
    }

    public function updateAdmin($user, array $data , int $siteSettingId)
    {
        $roleIds = $data['role_ids'] ?? [];
        unset($data['role_ids']);
        
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $updatedUser = $this->adminRepository->updateAdmin($user, $data);

        $updatedUser->gyms()->syncWithoutDetaching([$siteSettingId]);

        // Update roles
        if (!empty($roleIds)) {
            $roles = SpatieRole::whereIn('id', $roleIds)->get();
            $updatedUser->syncRoles($roles);
        }

        return $updatedUser;
    }

    public function deleteAdmin($user)
    {
        return $this->adminRepository->deleteAdmin($user);
    }

    /**
     * Check if admin is a branch manager
     */
    public function isAdminBranchManager(User $user): bool
    {
        return $user->branches()->exists();
    }

    /**
     * Get all admins except the one being deleted
     */
    public function getAvailableAdminsForReassignment(int $siteSettingId, int $excludeUserId)
    {
        $admins = $this->adminRepository->getAllAdminsWithoutPagination($siteSettingId);
        
        if ($excludeUserId > 0) {
            $admins = $admins->where('id', '!=', $excludeUserId);
        }
        
        return $admins;
    }

    /**
     * Reassign branch manager
     */
    public function reassignBranchManager(User $oldManager, User $newManager): void
    {
        $oldManager->branches()->update(['manager_id' => $newManager->id]);
    }

    /**
     * Check if admin has set their password
     */
    public function hasAdminSetPassword(User $user): bool
    {
        $tokenRecord = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        return !$tokenRecord;
    }

    /**
     * Send onboarding email to new admin
     */
    public function sendOnboardingEmail($user, int $siteSettingId): void
    {
        try {
            $gym = $user->gyms()->where('site_setting_id', $siteSettingId)->first();
            $gymName = $gym->gym_name;
            $gymSlug = $gym->slug;
            
            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );
            
            Mail::to($user->email)->send(new AdminOnboardingMail($user, $gymName, $gymSlug, $token));
        } catch (\Exception $e) {
            Log::error('Failed to send onboarding email to admin: ' . $user->email, [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
        }
    }
}