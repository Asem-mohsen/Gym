<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAssignmentService
{
    /**
     * Get all available permissions from the centralized definition
     */
    public function getAllPermissions(): array
    {
        return [
            // Blog permissions
            'create_blog_posts',
            'edit_blog_posts',
            'delete_blog_posts',
            'view_blog_posts',
            
            // User management permissions
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Membership permissions
            'view_memberships',
            'create_memberships',
            'edit_memberships',
            'delete_memberships',
            
            // Service permissions
            'view_services',
            'create_services',
            'edit_services',
            'delete_services',
            
            // Class permissions
            'view_classes',
            'create_classes',
            'edit_classes',
            'delete_classes',
            
            // Offer permissions
            'view_offers',
            'create_offers',
            'edit_offers',
            'delete_offers',
            
            // Financial permissions
            'view_financials',
            'view_payments',
            'create_payments',
            'edit_payments',
            'delete_payments',
            
            // Site settings permissions
            'manage_site_settings',
            'view_site_settings',
            'edit_site_settings',
            
            // Branch permissions
            'manage_branches',
            'view_branches',
            'create_branches',
            'edit_branches',
            'delete_branches',
            
            // Score management permissions
            'manage_scores',
            'view_scores',
            'edit_scores',
            
            // Role and permission management
            'manage_roles',
            'assign_roles',
            'view_roles',

            // Reviews requests permissions
            'view_reviews_requests',
            'create_reviews_requests',
            'edit_reviews_requests',
            'delete_reviews_requests',

            // Subscriptions permissions
            'view_subscriptions',
            'create_subscriptions',
            'edit_subscriptions',
            'delete_subscriptions',

            // Features permissions
            'view_features',
            'create_features',
            'edit_features',
            'delete_features',
            
            // Check-in settings permissions
            'view_checkin_settings',
            'create_checkin_settings',
            'edit_checkin_settings',
            'delete_checkin_settings',

            // Gallery permissions
            'view_gallery',
            'create_gallery',
            'edit_gallery',
            'delete_gallery',

            // Coaching sessions permissions
            'view_coaching_sessions',
            'create_coaching_sessions',
            'edit_coaching_sessions',
            'delete_coaching_sessions',

            // Lockers permissions
            'view_lockers',
            'create_lockers',
            'edit_lockers',
            'delete_lockers',

            // Booking permissions
            'view_bookings',
            'create_bookings',
            'edit_bookings',
            'delete_bookings',

            // Trainers permissions
            'view_trainers',
            'create_trainers',
            'edit_trainers',
            'delete_trainers',

            // Staff permissions
            'view_staff',
            'create_staff',
            'edit_staff',
            'delete_staff',

            // Admins permissions
            'view_admins',
            'create_admins',
            'edit_admins',
            'delete_admins',

            // Invitations permissions
            'view_invitations',
            'delete_invitations',

            // Resources permissions
            'view_resources',
            'download_resources',

            // Deactivation permissions
            'deactivate_gyms_and_branches',

            // Import permissions
            'import_gym_data',

            // Notification permissions
            'view_notifications',
            'create_notifications',
            'edit_notifications',
            'delete_notifications',

            // Contact permissions
            'view_contacts',
            'reply_to_contacts',

            // Branding permissions
            'view_branding',
            'create_branding',
            'edit_branding',
            'delete_branding',
        ];
    }

    /**
     * Get role permissions mapping
     */
    public function getRolePermissionsMapping(): array
    {
        $allPermissions = $this->getAllPermissions();
        
        return [
            'master_admin' => $allPermissions,
            'admin' => $allPermissions,
            'trainer' => [
                'create_blog_posts',
                'edit_blog_posts',
                'view_blog_posts',
                'view_classes',
                'view_users',
                'view_services',
                'view_trainers',
                'edit_trainers',
                'view_notifications',
            ],
            'sales' => [
                'view_users',
                'view_memberships',
                'view_services',
                'view_classes',
                'view_offers',
                'view_staff',
                'view_subscriptions',
                'create_subscriptions',
                'edit_subscriptions',
                'view_features',
                'view_gallery',
                'create_gallery',
                'edit_gallery',
                'view_coaching_sessions',
                'view_lockers',
                'create_blog_posts',
                'edit_blog_posts',
                'view_blog_posts',
                'view_trainers',
                'view_notifications',
            ],
            'management' => [
                'view_users',
                'create_users',
                'edit_users',
                'view_features',
                'create_features',
                'edit_features',
                'view_memberships',
                'create_memberships',
                'edit_memberships',
                'view_services',
                'create_services',
                'edit_services',
                'view_classes',
                'create_classes',
                'edit_classes',
                'view_offers',
                'create_offers',
                'edit_offers',
                'view_branches',
                'create_branches',
                'edit_branches',
                'view_site_settings',
                'edit_site_settings',
                'create_reviews_requests',
                'edit_reviews_requests',
                'view_reviews_requests',
                'view_subscriptions',
                'create_subscriptions',
                'edit_subscriptions',
                'view_trainers',
                'edit_trainers',
                'view_staff',
                'edit_staff',
                'delete_staff',
                'view_invitations',
                'delete_invitations',
                'view_admins',
                'edit_admins',
                'delete_trainers',
                'view_resources',
                'download_resources',
                'view_notifications',
                'create_notifications',
                'edit_notifications',
                'delete_notifications',
            ],
            'regular_user' => [],
        ];
    }

    /**
     * Create all permissions in the database
     */
    public function createAllPermissions(): void
    {
        $permissions = $this->getAllPermissions();
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /**
     * Create all roles in the database
     */
    public function createAllRoles(): void
    {
        $rolePermissions = $this->getRolePermissionsMapping();
        
        foreach (array_keys($rolePermissions) as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }

    /**
     * Assign permissions to roles for a specific site setting
     */
    public function assignRolePermissionsForSite(SiteSetting $siteSetting): void
    {
        $rolePermissions = $this->getRolePermissionsMapping();
        
        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role && !empty($permissionNames)) {
                $this->assignPermissionsToRoleForSite($role, $permissionNames, $siteSetting->id);
            }
        }
    }

    /**
     * Assign specific permissions to a role for a specific site
     */
    public function assignPermissionsToRoleForSite(Role $role, array $permissionNames, int $siteSettingId): void
    {
        // First, remove existing permissions for this role in this site
        DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->where('site_setting_id', $siteSettingId)
            ->delete();

        $insertData = [];
        foreach ($permissionNames as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $insertData[] = [
                    'permission_id' => $permission->id,
                    'role_id' => $role->id,
                    'site_setting_id' => $siteSettingId,
                ];
            }
        }

        if (!empty($insertData)) {
            DB::table('role_has_permissions')->insert($insertData);
        }
    }

    /**
     * Initialize all permissions and roles for a new site
     */
    public function initializeSitePermissions(SiteSetting $siteSetting): void
    {
        // Ensure all permissions exist
        $this->createAllPermissions();
        
        // Ensure all roles exist
        $this->createAllRoles();
        
        // Assign role permissions for this site
        $this->assignRolePermissionsForSite($siteSetting);
    }
}
