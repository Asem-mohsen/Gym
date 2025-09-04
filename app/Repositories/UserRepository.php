<?php 
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserRepository
{
    public function getAllUsers(int $siteSettingId)
    {
        $query = User::where('is_admin', '0')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'regular_user');
            })
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            });

        return $query->with('roles')->get();
    }

    public function getAllTrainers(int $siteSettingId, $perPage = 15, $branchId = null, $search = null)
    {
        $trainerRole = Role::where('name', 'trainer')->first();
        
        $query = User::where('is_admin', '0')
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            });

        if ($trainerRole) {
            $query->whereHas('roles', function ($query) use ($trainerRole) {
                $query->where('roles.id', $trainerRole->id);
            });
        }

        if ($branchId) {
            $query->whereHas('subscriptions', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->with('roles')->paginate($perPage);
    }

    public function getAllStaff(int $siteSettingId, ?int $branchId = null)
    {
        // Get staff roles (excluding admin and regular_user)
        $staffRoles = Role::whereNotIn('name', ['admin', 'regular_user', 'trainer'])->pluck('id');
        
        $query = User::where('is_admin', '0')
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('roles', function ($query) use ($staffRoles) {
                $query->whereIn('roles.id', $staffRoles);
            });

        if ($branchId) {
            $query->whereHas('subscriptions', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        }

        return $query->with('roles')->get();
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function updateUser(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user)
    {
        // Start transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // 1. Delete user's media files
            $user->clearMediaCollection('user_images');
            
            // 2. Delete user's API tokens
            $user->tokens()->delete();
            
            // 3. Delete password reset tokens from DB table
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            
            // 4. Delete user's bookings
            $user->bookings()->delete();
            
            // 5. Delete user's subscriptions
            $user->subscriptions()->delete();
            
            // 6. Delete user's sent invitations
            $user->sentInvitations()->delete();
            
            // 7. Delete user's used invitations (where they are the used_by)
            $user->usedInvitations()->delete();
            
            // 8. Delete user's trainer information
            if ($user->trainerInformation) {
                $user->trainerInformation()->delete();
            }
            
            // 9. Delete user's managed branches (set manager_id to null)
            $user->managedBranches()->update(['manager_id' => null]);
            
            // 10. Delete user's comments
            $user->comments()->delete();
            
            // 11. Delete user's comment likes
            $user->commentLikes()->detach();
            
            // 12. Delete user's blog posts
            $user->blogPosts()->delete();
            
            // 13. Delete user's blog post shares
            $user->blogPostShares()->delete();
            
            // 14. Delete user's payments
            $user->payments()->delete();
            
            // 15. Delete user's documents
            $user->documents()->delete();
            
            // 16. Delete user's branch score history entries
            $user->branchScoreHistory()->delete();
            
            // 17. Delete user's branch score review requests
            $user->branchScoreReviewRequests()->delete();
            
            // 18. Detach user from gyms (many-to-many relationship)
            $user->gyms()->detach();
            
            // 19. Detach user from classes (as trainer)
            $user->classes()->detach();
            
            // 20. Delete user's roles and permissions
            $user->roles()->detach();
            $user->permissions()->detach();
            
            // 21. Finally, force delete the user (hard delete)
            $user->forceDelete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function findById(int $id, array $with = []): ?User
    {
        return User::with($with)->find($id);
    }

    /**
     * Get users by role name
     */
    public function getUsersByRole(string $roleName, int $siteSettingId, $perPage = 15, $search = null)
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return collect()->paginate($perPage);
        }

        $query = User::where('is_admin', '0')
            ->whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('roles.id', $role->id);
            });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->with('roles')->paginate($perPage);
    }
}