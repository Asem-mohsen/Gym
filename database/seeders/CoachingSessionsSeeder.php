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
        $trainers = $this->userService->getTrainers();

        $users = $this->userService->getUsers();

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
