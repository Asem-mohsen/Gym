<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create 1 Admin
        User::create([
            'name' => 'Gym Admin',
            'email' => 'gym@gmail.com',
            'password'=>bcrypt('gym@gmail.com'),
            'address' => 'gym location',
            'gender' => 'male',
            'phone' => '01152992719',
            'status' => 1,
            'is_admin' => 1,
            'role_id' => 1,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Create the remaining 29 Users
        User::factory()->count(29)->state(['role_id' => 2])->create();
        
        // Create 10 Trainers
        User::factory()->count(10)->state(['role_id' => 3])->create();
    }
}
