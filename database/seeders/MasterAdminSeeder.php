<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Gymify Admin',
            'email' => 'gymify@gmail.com',
            'password' => bcrypt('gymify@gmail.com'),
            'password_set_at' => now(),
            'address' => 'gymify location',
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

        $admin->assignRole('master_admin');
    }
}
