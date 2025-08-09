<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGymAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all site settings
        $siteSettings = SiteSetting::all();
        
        if ($siteSettings->isEmpty()) {
            $this->command->warn('No site settings found. Skipping user-gym assignments...');
            return;
        }
        
        // Get all users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Skipping user-gym assignments...');
            return;
        }
        
        // Assign users to gyms
        foreach ($users as $user) {
            // Assign each user to the first site setting (or you can distribute them across multiple sites)
            $siteSetting = $siteSettings->first();
            
            // Check if the relationship already exists
            $exists = DB::table('gym_user')
                ->where('user_id', $user->id)
                ->where('site_setting_id', $siteSetting->id)
                ->exists();
            
            if (!$exists) {
                DB::table('gym_user')->insert([
                    'user_id' => $user->id,
                    'site_setting_id' => $siteSetting->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $this->command->info('Users assigned to gyms successfully!');
    }
}
