<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 Admins
        User::factory()->count(3)->state(['is_admin' => 1, 'role_id' => 1])->create();

        // Create the remaining 27 Users
        User::factory()->count(27)->state(['role_id' => 2])->create();
        
        // Create 10 Trainers
        User::factory()->count(10)->state(['role_id' => 3])->create();
    }
}
