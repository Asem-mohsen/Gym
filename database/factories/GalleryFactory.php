<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Gallery;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $galleryableType = $this->faker->randomElement([SiteSetting::class, Branch::class]);
        
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'galleryable_type' => $galleryableType,
            'galleryable_id' => $galleryableType === SiteSetting::class 
                ? SiteSetting::factory() 
                : Branch::factory(),
            'site_setting_id' => SiteSetting::factory(),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the gallery is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the gallery is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a gallery for a site setting.
     */
    public function forSiteSetting(SiteSetting $siteSetting): static
    {
        return $this->state(fn (array $attributes) => [
            'galleryable_type' => SiteSetting::class,
            'galleryable_id' => $siteSetting->id,
            'site_setting_id' => $siteSetting->id,
        ]);
    }

    /**
     * Create a gallery for a branch.
     */
    public function forBranch(Branch $branch): static
    {
        return $this->state(fn (array $attributes) => [
            'galleryable_type' => Branch::class,
            'galleryable_id' => $branch->id,
            'site_setting_id' => $branch->site_setting_id,
        ]);
    }
} 