<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        
        // Create services for each site setting
        foreach ($siteSettings as $siteSetting) {
            $services[] = [
                'name' => json_encode(['en' => 'Life Coaching', 'ar' => 'التدريب الحياتي']),
                'description' => json_encode(['en' => 'Description', 'ar' => 'الوصف 1']),
                'duration' => 30,
                'price' => 50.00,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $services[] = [
                'name' => json_encode(['en' => 'Diet Doctor', 'ar' => 'تنظيم غذائي']),
                'description' => json_encode(['en' => 'Description 2', 'ar' => 'الوصف 2']),
                'duration' => 45,
                'price' => 75.00,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        Service::insert($services);
    }
}
