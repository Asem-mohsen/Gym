<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{

    public function run(): void
    {
        // Get all site settings
        $siteSettings = SiteSetting::all();
        
        $roles = [];
        
        // Create roles for each site setting
        foreach ($siteSettings as $siteSetting) {
            $roles[] = [
                'name' => 'Admin',
                'description' => 'Administrator role',
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $roles[] = [
                'name' => 'System User',
                'description' => 'Regular user role',
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $roles[] = [
                'name' => 'Trainer',
                'description' => 'Trainer role',
                'site_setting_id' => $siteSetting->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        Role::insert($roles);
    }
}
