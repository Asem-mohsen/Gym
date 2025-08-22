<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{

    public function run(): void
    {
        $siteSettings = SiteSetting::all();

        if ($siteSettings->isEmpty()) {
            $this->call(SiteSettingSeeder::class);
            $siteSettings = SiteSetting::all();
        }
        
        $services = [];
        $sortOrder = 1;
        
        // Define gym services with their properties
        $gymServices = [
            [
                'name' => ['en' => 'Sauna', 'ar' => 'ساونا'],
                'description' => ['en' => 'Relaxing sauna session for detoxification and relaxation', 'ar' => 'جلسة ساونا مريحة لإزالة السموم والاسترخاء'],
                'duration' => 60,
                'price' => 25.00,
                'booking_type' => 'paid_booking',
                'requires_payment' => true,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Jacuzzi', 'ar' => 'جاكوزي'],
                'description' => ['en' => 'Therapeutic jacuzzi session for muscle recovery', 'ar' => 'جلسة جاكوزي علاجية لاستعادة العضلات'],
                'duration' => 45,
                'price' => 30.00,
                'booking_type' => 'paid_booking',
                'requires_payment' => true,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Steam Room', 'ar' => 'غرفة البخار'],
                'description' => ['en' => 'Steam room session for skin purification and relaxation', 'ar' => 'جلسة غرفة بخار لتنقية البشرة والاسترخاء'],
                'duration' => 30,
                'price' => 20.00,
                'booking_type' => 'paid_booking',
                'requires_payment' => true,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Ice Path', 'ar' => 'مسار الثلج'],
                'description' => ['en' => 'Ice therapy path for muscle recovery and inflammation reduction', 'ar' => 'مسار العلاج بالثلج لاستعادة العضلات وتقليل الالتهاب'],
                'duration' => 15,
                'price' => 15.00,
                'booking_type' => 'free_booking',
                'requires_payment' => false,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Massage Therapy', 'ar' => 'العلاج بالتدليك'],
                'description' => ['en' => 'Professional massage therapy for muscle relaxation and stress relief', 'ar' => 'علاج تدليك احترافي لاسترخاء العضلات وتخفيف التوتر'],
                'duration' => 60,
                'price' => 80.00,
                'booking_type' => 'paid_booking',
                'requires_payment' => true,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Swimming Pool', 'ar' => 'حمام السباحة'],
                'description' => ['en' => 'Access to swimming pool for cardio and low-impact exercise', 'ar' => 'الوصول إلى حمام السباحة للتمارين القلبية والتمارين منخفضة التأثير'],
                'duration' => 120,
                'price' => 40.00,
                'booking_type' => 'free_booking',
                'requires_payment' => false,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Personal Training', 'ar' => 'التدريب الشخصي'],
                'description' => ['en' => 'One-on-one personal training session with certified trainer', 'ar' => 'جلسة تدريب شخصي فردية مع مدرب معتمد'],
                'duration' => 60,
                'price' => 100.00,
                'booking_type' => 'paid_booking',
                'requires_payment' => true,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Group Fitness Classes', 'ar' => 'فصول اللياقة الجماعية'],
                'description' => ['en' => 'Group fitness classes including yoga, pilates, and cardio', 'ar' => 'فصول اللياقة الجماعية تشمل اليوجا والبيلاتس والكارديو'],
                'duration' => 45,
                'price' => 35.00,
                'booking_type' => 'free_booking',
                'requires_payment' => false,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Locker Room Access', 'ar' => 'الوصول إلى غرفة الخزائن'],
                'description' => ['en' => 'Access to locker room facilities and showers', 'ar' => 'الوصول إلى مرافق غرفة الخزائن والاستحمام'],
                'duration' => 0,
                'price' => 0.00,
                'booking_type' => 'unbookable',
                'requires_payment' => false,
                'is_available' => true,
            ],
            [
                'name' => ['en' => 'Equipment Access', 'ar' => 'الوصول إلى المعدات'],
                'description' => ['en' => 'Access to all gym equipment and machines', 'ar' => 'الوصول إلى جميع معدات وأجهزة الجيم'],
                'duration' => 0,
                'price' => 0.00,
                'booking_type' => 'unbookable',
                'requires_payment' => false,
                'is_available' => true,
            ],
        ];
        
        // Create services for each site setting
        foreach ($siteSettings as $siteSetting) {
            foreach ($gymServices as $serviceData) {
                $services[] = [
                    'name' => json_encode($serviceData['name']),
                    'description' => json_encode($serviceData['description']),
                    'duration' => $serviceData['duration'],
                    'price' => $serviceData['price'],
                    'booking_type' => $serviceData['booking_type'],
                    'requires_payment' => $serviceData['requires_payment'],
                    'is_available' => $serviceData['is_available'],
                    'sort_order' => $sortOrder++,
                    'site_setting_id' => $siteSetting->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        Service::insert($services);
        
        $this->assignServicesToBranches();
    }
    
    private function assignServicesToBranches()
    {
        $services = Service::all();
        $branches = Branch::all();
        
        foreach ($services as $service) {
            $siteBranches = $branches->where('site_setting_id', $service->site_setting_id);
            
            foreach ($siteBranches as $branch) {
                $service->branches()->attach($branch->id, [
                    'is_available' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
