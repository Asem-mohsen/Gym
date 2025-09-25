<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole   = Role::firstOrCreate(['name' => 'admin']);
        $userRole    = Role::firstOrCreate(['name' => 'regular_user']);
        $trainerRole = Role::firstOrCreate(['name' => 'trainer']);

        // Create 1 Admin
        $admin = User::create([
            'name' => 'Gym Admin',
            'email' => 'gym@gmail.com',
            'password' => bcrypt('gym@gmail.com'),
            'password_set_at' => now(),
            'address' => 'gym location',
            'gender' => 'male',
            'country' => 'Egypt',
            'city' => 'Cairo',
            'last_visit_at' => now(),
            'phone' => '01152992719',
            'status' => 1,
            'is_admin' => 1,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $admin->assignRole($adminRole);

        // Create the remaining 29 Users and assign 'user' role
        User::factory()->count(29)->create()->each(function ($user) use ($userRole) {
            $user->assignRole($userRole);
        });

        // Create 10 Trainers and assign 'trainer' role
        User::factory()->count(10)->create()->each(function ($trainer) use ($trainerRole) {
            $trainer->assignRole($trainerRole);
        });
    }
}
