<?php

namespace Database\Seeders;

use App\Services\UserService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoachingSessionsSeeder extends Seeder
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function run(): void
    {
        // Get the first site setting ID or create one if none exists
        $siteSettingId = \App\Models\SiteSetting::first()?->id ?? 1;
        
        $trainers = $this->userService->getTrainers(siteSettingId: $siteSettingId);
        $users = $this->userService->getUsers(siteSettingId: $siteSettingId);

        // Check if we have trainers and users available
        if ($trainers->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No trainers or users available for coaching sessions. Skipping...');
            return;
        }

        // Create coaching sessions
        foreach (range(1, 20) as $index) {
            DB::table('coaching_sessions')->insert([
                'coach_id' => $trainers->random()->id,
                'user_id' => $users->random()->id,
                'cost' => rand(50, 200),
                'period' => rand(30, 120) . ' hours',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
