<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all site settings
        $siteSettings = SiteSetting::all();
        
        if ($siteSettings->isEmpty()) {
            $this->command->warn('No site settings found. Skipping branch creation...');
            return;
        }
        
        // Get admin users to assign as managers
        $adminUsers = User::whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->get();
        
        if ($adminUsers->isEmpty()) {
            $this->command->warn('No admin users found. Skipping branch creation...');
            return;
        }
        
        // Create branches for each site setting
        foreach ($siteSettings as $siteSetting) {
            // Create 2-3 branches per site
            $branchCount = rand(2, 3);
            
            for ($i = 0; $i < $branchCount; $i++) {
                $manager = $adminUsers->random();
                
                // Get gym name and address safely, handling both array and string formats
                $gymNameEn = $this->getTranslatableValue($siteSetting->gym_name, 'en', 'Elite Fitness Gym');
                $gymNameAr = $this->getTranslatableValue($siteSetting->gym_name, 'ar', 'صالة النخبة الرياضية');
                $addressEn = $this->getTranslatableValue($siteSetting->address, 'en', '123 Fitness Street, Sports District, City');
                $addressAr = $this->getTranslatableValue($siteSetting->address, 'ar', '١٢٣ شارع اللياقة، حي الرياضة، المدينة');
                
                Branch::create([
                    'manager_id' => $manager->id,
                    'site_setting_id' => $siteSetting->id,
                    'name' => [
                        'en' => $gymNameEn . ' - Branch ' . ($i + 1),
                        'ar' => $gymNameAr . ' - فرع ' . ($i + 1)
                    ],
                    'location' => [
                        'en' => 'Branch ' . ($i + 1) . ' Location, ' . $addressEn,
                        'ar' => 'موقع الفرع ' . ($i + 1) . '، ' . $addressAr
                    ],
                    'type' => $this->getRandomBranchType(),
                    'size' => rand(2000, 6000),
                    'facebook_url' => 'https://facebook.com/branch' . ($i + 1),
                    'instagram_url' => 'https://instagram.com/branch' . ($i + 1),
                    'x_url' => 'https://x.com/branch' . ($i + 1),
                ]);
            }
        }
        
        $this->command->info('Branches created successfully!');
    }
    
    /**
     * Get a random branch type
     */
    private function getRandomBranchType(): string
    {
        $types = ['mix', 'women', 'men'];
        return $types[array_rand($types)];
    }
    
    /**
     * Safely get translatable value, handling both array and string formats
     */
    private function getTranslatableValue($field, string $locale, string $default = ''): string
    {
        if (is_array($field) && isset($field[$locale])) {
            return $field[$locale];
        }
        
        if (is_string($field)) {
            // Try to decode JSON if it's a JSON string
            $decoded = json_decode($field, true);
            if (is_array($decoded) && isset($decoded[$locale])) {
                return $decoded[$locale];
            }
            return $field;
        }
        
        return $default;
    }
}
