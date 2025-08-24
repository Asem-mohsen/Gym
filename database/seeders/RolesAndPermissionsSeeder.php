<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
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

            // Blog posts permissions
            'view_blog_posts',
            'create_blog_posts',
            'edit_blog_posts',
            'delete_blog_posts',

            // Booking permissions
            'view_bookings',
            'create_bookings',
            'edit_bookings',
            'delete_bookings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->createAdminRole();
        $this->createTrainerRole();
        $this->createSalesRole();
        $this->createManagementRole();
        $this->createRegularUserRole();
    }

    private function createAdminRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        
        $role->givePermissionTo(Permission::all());
    }

    private function createTrainerRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'trainer']);
        
        // Trainer can manage blog posts
        $role->givePermissionTo([
            'create_blog_posts',
            'edit_blog_posts',
            'view_blog_posts',
            'view_classes',
            'view_users',
            'view_services',
        ]);
    }

    private function createSalesRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'sales']);
        
        // Sales can view but not create/edit
        $role->givePermissionTo([
            'view_users',
            'view_memberships',
            'view_services',
            'view_classes',
            'view_offers',
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
        ]);
    }

    private function createManagementRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'management']);
        
        // Management can create, edit, and view
        $role->givePermissionTo([
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
        ]);
    }

    private function createRegularUserRole(): void
    {
        Role::firstOrCreate(['name' => 'regular_user']);
    }
}
