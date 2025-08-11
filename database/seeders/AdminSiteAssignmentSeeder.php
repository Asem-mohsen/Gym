<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSiteAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all admin users
        $adminUsers = User::whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->get();
        
        // Get all site settings
        $siteSettings = SiteSetting::all();
        
        if ($adminUsers->isNotEmpty() && $siteSettings->isNotEmpty()) {
            // Assign admin users to site settings
            foreach ($adminUsers as $index => $adminUser) {
                if (isset($siteSettings[$index])) {
                    $siteSettings[$index]->update([
                        'owner_id' => $adminUser->id
                    ]);
                }
            }
            
            // If we have more site settings than admin users, assign remaining sites to the first admin
            if ($siteSettings->count() > $adminUsers->count()) {
                for ($i = $adminUsers->count(); $i < $siteSettings->count(); $i++) {
                    $siteSettings[$i]->update([
                        'owner_id' => $adminUsers->first()->id
                    ]);
                }
            }
        }
    }
}
