<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            FeaturesSeeder::class,
            MembershipsSeeder::class,
            ServicesSeeder::class,
            CoachingSessionsSeeder::class,
            LockerSeeder::class,
            GallerySeeder::class,
        ]);

    }
}
