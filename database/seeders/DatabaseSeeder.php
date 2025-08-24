<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            SiteSettingSeeder::class,
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            AdminSiteAssignmentSeeder::class,
            UserGymAssignmentSeeder::class,
            FeaturesSeeder::class,
            MembershipsSeeder::class,
            ServicesSeeder::class,
            BranchSeeder::class,
            CoachingSessionsSeeder::class,
            LockerSeeder::class,
            GallerySeeder::class,
        ]);

    }
}
