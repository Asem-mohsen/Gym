<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default site setting first (this will be the main site)
        $defaultSiteSetting = SiteSetting::firstOrCreate(
            ['id' => 1],
            [
                'owner_id' => null, // Will be updated later
                'gym_name' => [
                    'en' => 'Nitro Gym',
                    'ar' => 'صالة النخبة الرياضية'
                ],
                'slug' => 'nitro',
                'size' => 5000,
                'address' => [
                    'en' => '123 Fitness Street, Sports District, City',
                    'ar' => '١٢٣ شارع اللياقة، حي الرياضة، المدينة'
                ],
                'description' => [
                    'en' => 'Premium fitness facility offering state-of-the-art equipment and professional training services.',
                    'ar' => 'منشأة لياقة بدنية فاخرة تقدم معدات متطورة وخدمات تدريب احترافية.'
                ],
                'contact_email' => 'info@elitefitness.com',
                'site_url' => 'https://elitefitness.com',
            ]
        );
        
        // Create additional site settings if needed (for future expansion)
        if (SiteSetting::count() < 3) {
            SiteSetting::factory(2)->create([
                'owner_id' => null
            ]);
        }
    }
}
