<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipsSeeder extends Seeder
{

    public function run(): void
    {
        // Get all site settings
        $siteSettings = SiteSetting::all();
        
        // If no site settings exist, create a default one
        if ($siteSettings->isEmpty()) {
            $this->call(SiteSettingSeeder::class);
            $siteSettings = SiteSetting::all();
        }
        
        $memberships = [];
        
        // Create memberships for each site setting
        foreach ($siteSettings as $siteSetting) {
            $memberships[] = [
                'name' => json_encode(['en' => 'Basic', 'ar' => 'أساسي']),
                'period' => '1 Month',
                'description' => json_encode(['en' => 'Basic membership description', 'ar' => 'وصف العضوية الأساسية']),
                'price' => 30.00,
                'status' => 1,
                'order' => 1,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $memberships[] = [
                'name' => json_encode(['en' => 'Standard', 'ar' => 'قياسي']),
                'period' => '3 Months',
                'description' => json_encode(['en' => 'Standard membership description', 'ar' => 'وصف العضوية القياسية']),
                'price' => 75.00,
                'status' => 1,
                'order' => 2,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $memberships[] = [
                'name' => json_encode(['en' => 'VIP', 'ar' => 'امتياز']),
                'period' => '3 Months',
                'description' => json_encode(['en' => 'VIP membership description', 'ar' => 'وصف العضوية القياسية']),
                'price' => 375.00,
                'status' => 1,
                'order' => 2,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        Membership::insert($memberships);
    }
}
