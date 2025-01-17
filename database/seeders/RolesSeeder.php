<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{

    public function run(): void
    {
        Role::insert([
            ['name' => 'Admin', 'description' => 'Administrator role', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'System User', 'description' => 'Regular user role', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trainer', 'description' => 'Trainer role', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
