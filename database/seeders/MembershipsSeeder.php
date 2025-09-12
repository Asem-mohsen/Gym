<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\SiteSetting;
use App\Enums\MembershipPeriod;
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
                'period' => MembershipPeriod::MONTH->value,
                'description' => json_encode(['en' => 'Basic membership description', 'ar' => 'وصف العضوية الأساسية']),
                'price' => 30.00,
                'billing_interval' => MembershipPeriod::MONTH->getBillingInterval(),
                'status' => 1,
                'order' => 1,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $memberships[] = [
                'name' => json_encode(['en' => 'Standard', 'ar' => 'قياسي']),
                'period' => MembershipPeriod::THREE_MONTHS->value,
                'description' => json_encode(['en' => 'Standard membership description', 'ar' => 'وصف العضوية القياسية']),
                'price' => 75.00,
                'billing_interval' => MembershipPeriod::THREE_MONTHS->getBillingInterval(),
                'status' => 1,
                'order' => 2,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $memberships[] = [
                'name' => json_encode(['en' => 'VIP', 'ar' => 'امتياز']),
                'period' => MembershipPeriod::YEAR->value,
                'description' => json_encode(['en' => 'VIP membership description', 'ar' => 'وصف العضوية المميزة']),
                'price' => 375.00,
                'billing_interval' => MembershipPeriod::YEAR->getBillingInterval(),
                'status' => 1,
                'order' => 3,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $memberships[] = [
                'name' => json_encode(['en' => 'Day Pass', 'ar' => 'تذكرة يومية']),
                'period' => MembershipPeriod::DAY->value,
                'description' => json_encode(['en' => 'One day access pass', 'ar' => 'تذكرة دخول ليوم واحد']),
                'price' => 5.00,
                'billing_interval' => MembershipPeriod::DAY->getBillingInterval(),
                'status' => 1,
                'order' => 4,
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        Membership::insert($memberships);
    }
}
