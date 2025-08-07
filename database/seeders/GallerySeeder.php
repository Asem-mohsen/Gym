<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Gallery;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing site settings and branches
        $siteSettings = SiteSetting::all();
        $branches = Branch::all();

        // Create galleries for site settings
        foreach ($siteSettings as $siteSetting) {
            // Main gym gallery
            Gallery::factory()->create([
                'title' => 'Main Gym Gallery',
                'description' => 'Showcasing our state-of-the-art equipment and facilities',
                'galleryable_type' => SiteSetting::class,
                'galleryable_id' => $siteSetting->id,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            // Events gallery
            Gallery::factory()->create([
                'title' => 'Events & Activities',
                'description' => 'Highlights from our gym events, competitions, and special activities',
                'galleryable_type' => SiteSetting::class,
                'galleryable_id' => $siteSetting->id,
                'is_active' => true,
                'sort_order' => 2,
            ]);

            // Before & After gallery
            Gallery::factory()->create([
                'title' => 'Success Stories',
                'description' => 'Inspiring transformations and success stories from our members',
                'galleryable_type' => SiteSetting::class,
                'galleryable_id' => $siteSetting->id,
                'is_active' => true,
                'sort_order' => 3,
            ]);
        }

        // Create galleries for branches
        foreach ($branches as $branch) {
            // Branch specific gallery
            Gallery::factory()->create([
                'title' => $branch->name . ' Branch Gallery',
                'description' => 'Exclusive photos from ' . $branch->name . ' branch',
                'galleryable_type' => Branch::class,
                'galleryable_id' => $branch->id,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            // Branch equipment gallery
            Gallery::factory()->create([
                'title' => $branch->name . ' Equipment',
                'description' => 'Equipment and facilities available at ' . $branch->name . ' branch',
                'galleryable_type' => Branch::class,
                'galleryable_id' => $branch->id,
                'is_active' => true,
                'sort_order' => 2,
            ]);
        }

        // Create some inactive galleries for testing
        Gallery::factory()->count(5)->inactive()->create();
    }
} 