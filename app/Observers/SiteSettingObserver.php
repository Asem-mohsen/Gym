<?php

namespace App\Observers;

use App\Models\Feature;
use App\Models\SiteSetting;
use App\Traits\ClearsPermissionCache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SiteSettingObserver
{
    use ClearsPermissionCache;
    /**
     * Handle the SiteSetting "created" event.
     */
    public function created(SiteSetting $siteSetting): void
    {
        $this->createDefaultGymPermissions($siteSetting);
        $this->createDefaultFeatures($siteSetting);
        $this->createDefaultRolesAndPermissions($siteSetting);
    }

    /**
     * Create default gym permissions for a new site setting
     */
    private function createDefaultGymPermissions(SiteSetting $siteSetting): void
    {
        if ($siteSetting->owner_id) {
            $owner = $siteSetting->owner;
            if ($owner) {
                // Assign admin role to the owner
                if (!$owner->hasRole('admin')) {
                    $owner->assignRole('admin');
                }
            }
        }
    }

    /**
     * Create default features for a new site setting
     */
    private function createDefaultFeatures(SiteSetting $siteSetting): void
    {
        $defaultFeatures = [
            [
                'name' => ['en' => '24/7 Access', 'ar' => 'وصول 24/7'],
                'description' => ['en' => 'Access to the gym 24 hours a day, 7 days a week', 'ar' => 'الوصول إلى الصالة الرياضية على مدار 24 ساعة في اليوم، 7 أيام في الأسبوع'],
                'status' => true,
                'order' => 1,
            ],
            [
                'name' => ['en' => 'Personal Trainer', 'ar' => 'مدرب شخصي'],
                'description' => ['en' => 'One-on-one personal training sessions', 'ar' => 'جلسات تدريب شخصي فردية'],
                'status' => true,
                'order' => 2,
            ],
            [
                'name' => ['en' => 'Group Classes', 'ar' => 'فصول جماعية'],
                'description' => ['en' => 'Access to all group fitness classes', 'ar' => 'الوصول إلى جميع فصول اللياقة البدنية الجماعية'],
                'status' => true,
                'order' => 3,
            ],
            [
                'name' => ['en' => 'Locker Room', 'ar' => 'غرفة تبديل الملابس'],
                'description' => ['en' => 'Access to locker room and shower facilities', 'ar' => 'الوصول إلى غرفة تبديل الملابس ومرافق الاستحمام'],
                'status' => true,
                'order' => 4,
            ],
            [
                'name' => ['en' => 'Sauna & Steam Room', 'ar' => 'ساونا وغرفة البخار'],
                'description' => ['en' => 'Access to sauna and steam room facilities', 'ar' => 'الوصول إلى مرافق الساونا وغرفة البخار'],
                'status' => true,
                'order' => 5,
            ],
            [
                'name' => ['en' => 'Towel Service', 'ar' => 'خدمة المناشف'],
                'description' => ['en' => 'Complimentary towel service', 'ar' => 'خدمة مناشف مجانية'],
                'status' => true,
                'order' => 6,
            ],
            [
                'name' => ['en' => 'Nutrition Consultation', 'ar' => 'استشارة التغذية'],
                'description' => ['en' => 'Free nutrition consultation sessions', 'ar' => 'جلسات استشارة تغذية مجانية'],
                'status' => true,
                'order' => 7,
            ],
            [
                'name' => ['en' => 'Guest Passes', 'ar' => 'تذاكر الضيوف'],
                'description' => ['en' => 'Monthly guest passes for friends and family', 'ar' => 'تذاكر ضيوف شهرية للأصدقاء والعائلة'],
                'status' => true,
                'order' => 8,
            ],
        ];

        foreach ($defaultFeatures as $featureData) {
            $featureData['site_setting_id'] = $siteSetting->id;
            Feature::create($featureData);
        }
    }

    /**
     * Create default roles and permissions for a new site setting
     */
    private function createDefaultRolesAndPermissions(SiteSetting $siteSetting): void
    {
        // Define default permissions for each role
        $rolePermissions = [
            'admin' => Permission::all()->pluck('name')->toArray(),
            'trainer' => [
                'create_blog_posts',
                'edit_blog_posts',
                'view_blog_posts',
                'view_classes',
                'view_users',
                'view_services',
                'view_trainers',
                'edit_trainers',
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
            ],
            'regular_user' => [], // No permissions by default
        ];

        // Create role-permission associations for this site setting
        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                $permissions = Permission::whereIn('name', $permissionNames)->get();
                
                foreach ($permissions as $permission) {
                    // Use insertOrIgnore to avoid duplicate key errors
                    DB::table('role_has_permissions')->insertOrIgnore([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id,
                        'site_setting_id' => $siteSetting->id,
                    ]);
                }
            }
        }
    }

    /**
     * Handle the SiteSetting "updated" event.
     */
    public function updated(SiteSetting $siteSetting): void
    {
        //
    }

    /**
     * Handle the SiteSetting "deleted" event.
     */
    public function deleted(SiteSetting $siteSetting): void
    {
        // Delete all gym role and user permissions for this site setting
        DB::table('role_has_permissions')
            ->where('site_setting_id', $siteSetting->id)
            ->delete();
        DB::table('model_has_permissions')
            ->where('site_setting_id', $siteSetting->id)
            ->delete();

        // Clear cache after gym deletion
        $this->clearPermissionCache();
    }

    /**
     * Handle the SiteSetting "restored" event.
     */
    public function restored(SiteSetting $siteSetting): void
    {
        //
    }

    /**
     * Handle the SiteSetting "force deleted" event.
     */
    public function forceDeleted(SiteSetting $siteSetting): void
    {
        DB::table('role_has_permissions')
            ->where('site_setting_id', $siteSetting->id)
            ->delete();
        DB::table('model_has_permissions')
            ->where('site_setting_id', $siteSetting->id)
            ->delete();

        // Clear cache after gym deletion
        $this->clearPermissionCache();
    }
}
