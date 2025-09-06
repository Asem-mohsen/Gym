<?php

namespace App\Observers;

use App\Models\Feature;
use App\Models\SiteSetting;
use App\Services\PermissionAssignmentService;
use App\Traits\ClearsPermissionCache;
use Illuminate\Support\Facades\DB;

class SiteSettingObserver
{
    use ClearsPermissionCache;
    
    public function __construct(
        protected PermissionAssignmentService $permissionAssignmentService
    ) {}

    /**
     * Handle the SiteSetting "created" event.
     */
    public function created(SiteSetting $siteSetting): void
    {
        $this->createDefaultGymPermissions($siteSetting);
        $this->createDefaultFeatures($siteSetting);
        $this->initializeSitePermissions($siteSetting);
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
        ];

        foreach ($defaultFeatures as $featureData) {
            $featureData['site_setting_id'] = $siteSetting->id;
            Feature::create($featureData);
        }
    }

    /**
     * Initialize site permissions using the centralized service
     */
    private function initializeSitePermissions(SiteSetting $siteSetting): void
    {
        $this->permissionAssignmentService->initializeSitePermissions($siteSetting);
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

        $this->clearPermissionCache();
    }
}
