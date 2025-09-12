<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\GymPermissionRepository;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class GymPermissionService
{
    public function __construct(
        protected GymPermissionRepository $gymPermissionRepository,
        protected SiteSettingService $siteSettingService
    ) {}

    /**
     * Assign permissions to a role for a specific gym
     */
    public function assignPermissionsToRole(Role $role, array $permissionNames, int $siteSettingId): void
    {
        $this->gymPermissionRepository->assignPermissionsToRole($role, $permissionNames, $siteSettingId);
    }

    /**
     * Assign permissions to a user for a specific gym
     */
    public function assignPermissionsToUser(User $user, array $permissionNames, int $siteSettingId): void
    {
        $this->gymPermissionRepository->assignPermissionsToUser($user, $permissionNames, $siteSettingId);
    }

    /**
     * Get role permissions for a specific gym
     */
    public function getRolePermissions(Role $role, int $siteSettingId): Collection
    {
        return $this->gymPermissionRepository->getRolePermissions($role, $siteSettingId);
    }

    /**
     * Get user permissions for a specific gym
     */
    public function getUserPermissions(User $user, int $siteSettingId): Collection
    {
        return $this->gymPermissionRepository->getUserPermissions($user, $siteSettingId);
    }

    /**
     * Get all available permission groups for organization
     */
    public function getPermissionGroups(): array
    {
        return [
            'user_management' => [
                'label' => 'User Management',
                'permissions' => [
                    'view_users' => 'View Users',
                    'create_users' => 'Create Users',
                    'edit_users' => 'Edit Users',
                    'delete_users' => 'Delete Users',
                ]
            ],
            'role_management' => [
                'label' => 'Role Management',
                'permissions' => [
                    'manage_roles' => 'Manage Roles',
                    'assign_roles' => 'Assign Roles',
                    'view_roles' => 'View Roles',
                ]
            ],
            'membership_management' => [
                'label' => 'Membership Management',
                'permissions' => [
                    'view_memberships' => 'View Memberships',
                    'create_memberships' => 'Create Memberships',
                    'edit_memberships' => 'Edit Memberships',
                    'delete_memberships' => 'Delete Memberships',
                ]
            ],
            'service_management' => [
                'label' => 'Service Management',
                'permissions' => [
                    'view_services' => 'View Services',
                    'create_services' => 'Create Services',
                    'edit_services' => 'Edit Services',
                    'delete_services' => 'Delete Services',
                ]
            ],
            'class_management' => [
                'label' => 'Class Management',
                'permissions' => [
                    'view_classes' => 'View Classes',
                    'create_classes' => 'Create Classes',
                    'edit_classes' => 'Edit Classes',
                    'delete_classes' => 'Delete Classes',
                ]
            ],
            'financial_management' => [
                'label' => 'Financial Management',
                'permissions' => [
                    'view_financials' => 'View Financials',
                    'view_payments' => 'View Payments',
                    'create_payments' => 'Create Payments',
                    'edit_payments' => 'Edit Payments',
                    'delete_payments' => 'Delete Payments',
                ]
            ],
            'site_settings' => [
                'label' => 'Site Settings',
                'permissions' => [
                    'manage_site_settings' => 'Manage Site Settings',
                    'view_site_settings' => 'View Site Settings',
                    'edit_site_settings' => 'Edit Site Settings',
                ]
            ],
            'branch_management' => [
                'label' => 'Branch Management',
                'permissions' => [
                    'manage_branches' => 'Manage Branches',
                    'view_branches' => 'View Branches',
                    'create_branches' => 'Create Branches',
                    'edit_branches' => 'Edit Branches',
                    'delete_branches' => 'Delete Branches',
                ]
            ],
            'score_management' => [
                'label' => 'Score Management',
                'permissions' => [
                    'manage_scores' => 'Manage Scores',
                    'view_scores' => 'View Scores',
                    'edit_scores' => 'Edit Scores',
                ]
            ],
            'blog_management' => [
                'label' => 'Blog Management',
                'permissions' => [
                    'view_blog_posts' => 'View Blog Posts',
                    'create_blog_posts' => 'Create Blog Posts',
                    'edit_blog_posts' => 'Edit Blog Posts',
                    'delete_blog_posts' => 'Delete Blog Posts',
                ]
            ],
            'offer_management' => [
                'label' => 'Offer Management',
                'permissions' => [
                    'view_offers' => 'View Offers',
                    'create_offers' => 'Create Offers',
                    'edit_offers' => 'Edit Offers',
                    'delete_offers' => 'Delete Offers',
                ]
            ],
            'subscription_management' => [
                'label' => 'Subscription Management',
                'permissions' => [
                    'view_subscriptions' => 'View Subscriptions',
                    'create_subscriptions' => 'Create Subscriptions',
                    'edit_subscriptions' => 'Edit Subscriptions',
                    'delete_subscriptions' => 'Delete Subscriptions',
                ]
            ],
            'feature_management' => [
                'label' => 'Feature Management',
                'permissions' => [
                    'view_features' => 'View Features',
                    'create_features' => 'Create Features',
                    'edit_features' => 'Edit Features',
                    'delete_features' => 'Delete Features',
                ]
            ],
            'gallery_management' => [
                'label' => 'Gallery Management',
                'permissions' => [
                    'view_gallery' => 'View Gallery',
                    'create_gallery' => 'Create Gallery',
                    'edit_gallery' => 'Edit Gallery',
                    'delete_gallery' => 'Delete Gallery',
                ]
            ],
            'coaching_management' => [
                'label' => 'Coaching Management',
                'permissions' => [
                    'view_coaching_sessions' => 'View Coaching Sessions',
                    'create_coaching_sessions' => 'Create Coaching Sessions',
                    'edit_coaching_sessions' => 'Edit Coaching Sessions',
                    'delete_coaching_sessions' => 'Delete Coaching Sessions',
                ]
            ],
            'locker_management' => [
                'label' => 'Locker Management',
                'permissions' => [
                    'view_lockers' => 'View Lockers',
                    'create_lockers' => 'Create Lockers',
                    'edit_lockers' => 'Edit Lockers',
                    'delete_lockers' => 'Delete Lockers',
                ]
            ],
            'booking_management' => [
                'label' => 'Booking Management',
                'permissions' => [
                    'view_bookings' => 'View Bookings',
                    'create_bookings' => 'Create Bookings',
                    'edit_bookings' => 'Edit Bookings',
                    'delete_bookings' => 'Delete Bookings',
                ]
            ],
            'trainer_management' => [
                'label' => 'Trainer Management',
                'permissions' => [
                    'view_trainers' => 'View Trainers',
                    'create_trainers' => 'Create Trainers',
                    'edit_trainers' => 'Edit Trainers',
                    'delete_trainers' => 'Delete Trainers',
                ]
            ],
            'staff_management' => [
                'label' => 'Staff Management',
                'permissions' => [
                    'view_staff' => 'View Staff',
                    'create_staff' => 'Create Staff',
                    'edit_staff' => 'Edit Staff',
                    'delete_staff' => 'Delete Staff',
                ]
            ],
            'admin_management' => [
                'label' => 'Admin Management',
                'permissions' => [
                    'view_admins' => 'View Admins',
                    'create_admins' => 'Create Admins',
                    'edit_admins' => 'Edit Admins',
                    'delete_admins' => 'Delete Admins',
                ]
            ],
            'invitation_management' => [
                'label' => 'Invitation Management',
                'permissions' => [
                    'view_invitations' => 'View Invitations',
                    'delete_invitations' => 'Delete Invitations',
                ]
            ],
            'resource_management' => [
                'label' => 'Resource Management',
                'permissions' => [
                    'view_resources' => 'View Resources',
                    'download_resources' => 'Download Resources',
                ]
            ],
            'Checkin_management' => [
                'label' => 'Checkin Settings',
                'permissions' => [
                    'view_checkin_settings' => 'View Checkin Settings',
                    'create_checkin_settings' => 'Create Checkin Settings',
                    'edit_checkin_settings' => 'Edit Checkin Settings',
                    'delete_checkin_settings' => 'Delete Checkin Settings',
                ]
            ],
            'review_management' => [
                'label' => 'Review Management',
                'permissions' => [
                    'view_reviews_requests' => 'View Review Requests',
                    'create_reviews_requests' => 'Create Review Requests',
                    'edit_reviews_requests' => 'Edit Review Requests',
                    'delete_reviews_requests' => 'Delete Review Requests',
                ]
            ],
            'contact_management' => [
                'label' => 'Contact Management',
                'permissions' => [
                    'view_contacts' => 'View Contacts',
                    'reply_to_contacts' => 'Reply to Contacts',
                ]
            ],
            'notification_management' => [
                'label' => 'Notification Management',
                'permissions' => [
                    'view_notifications' => 'View Notifications',
                    'create_notifications' => 'Create Notifications',
                    'edit_notifications' => 'Edit Notifications',
                    'delete_notifications' => 'Delete Notifications',
                ]
            ],
            'branding_management' => [
                'label' => 'Branding And Design Control',
                'permissions' => [
                    'view_branding' => 'View Branding',
                    'create_branding' => 'Create Branding',
                    'edit_branding' => 'Edit Branding',
                    'delete_branding' => 'Delete Branding',
                ]
            ],
            'import_management' => [
                'label' => 'Import Management',
                'permissions' => [
                    'import_gym_data' => 'Import Gym Data',
                ]
            ],
            'deactivation_management' => [
                'label' => 'Deactivation Management',
                'permissions' => [
                    'deactivate_gyms_and_branches' => 'Deactivate Gyms and Branches',
                ]
            ],
        ];
    }
}
