<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeaturesSeeder extends Seeder
{
    public function run(): void
    {
        $siteSetting = SiteSetting::first();
        
        if (!$siteSetting) {
            throw new \Exception('No site setting found. Please create a site setting first.');
        }

        Feature::insert([
            [
                'site_setting_id' => $siteSetting->id,
                'name' => json_encode(['en' => '24/7 Access', 'ar' => 'وصول 24/7']),
                'description' => json_encode(['en' => 'Access to the gym 24 hours a day, 7 days a week', 'ar' => 'الوصول إلى الصالة الرياضية على مدار 24 ساعة في اليوم، 7 أيام في الأسبوع']),
                'status' => 1,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_setting_id' => $siteSetting->id,
                'name' => json_encode(['en' => 'Personal Trainer', 'ar' => 'مدرب شخصي']),
                'description' => json_encode(['en' => 'One-on-one personal training sessions', 'ar' => 'جلسات تدريب شخصي فردية']),
                'status' => 1,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_setting_id' => $siteSetting->id,
                'name' => json_encode(['en' => 'Group Classes', 'ar' => 'فصول جماعية']),
                'description' => json_encode(['en' => 'Access to all group fitness classes', 'ar' => 'الوصول إلى جميع فصول اللياقة البدنية الجماعية']),
                'status' => 1,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_setting_id' => $siteSetting->id,
                'name' => json_encode(['en' => 'Locker Room', 'ar' => 'غرفة تبديل الملابس']),
                'description' => json_encode(['en' => 'Access to locker room and shower facilities', 'ar' => 'الوصول إلى غرفة تبديل الملابس ومرافق الاستحمام']),
                'status' => 1,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_setting_id' => $siteSetting->id,
                'name' => json_encode(['en' => 'Sauna & Steam Room', 'ar' => 'ساونا وغرفة البخار']),
                'description' => json_encode(['en' => 'Access to sauna and steam room facilities', 'ar' => 'الوصول إلى مرافق الساونا وغرفة البخار']),
                'status' => 1,
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_setting_id' => $siteSetting->id,
                'name' => json_encode(['en' => 'Towel Service', 'ar' => 'خدمة المناشف']),
                'description' => json_encode(['en' => 'Complimentary towel service', 'ar' => 'خدمة مناشف مجانية']),
                'status' => 1,
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_setting_id' => $siteSetting->id,
                'name' => json_encode(['en' => 'Nutrition Consultation', 'ar' => 'استشارة التغذية']),
                'description' => json_encode(['en' => 'Free nutrition consultation sessions', 'ar' => 'جلسات استشارة تغذية مجانية']),
                'status' => 1,
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_setting_id' => $siteSetting->id,
                'name' => json_encode(['en' => 'Guest Passes', 'ar' => 'تذاكر الضيوف']),
                'description' => json_encode(['en' => 'Monthly guest passes for friends and family', 'ar' => 'تذاكر ضيوف شهرية للأصدقاء والعائلة']),
                'status' => 1,
                'order' => 8,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
