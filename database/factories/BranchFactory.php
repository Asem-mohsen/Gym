<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'manager_id' => User::factory(),
            'site_setting_id' => SiteSetting::factory(),
            'name' => [
                'en' => $this->faker->company() . ' Branch',
                'ar' => 'فرع ' . $this->faker->company()
            ],
            'location' => [
                'en' => $this->faker->address(),
                'ar' => $this->faker->address()
            ],
            'type' => $this->faker->randomElement(['mix', 'women', 'men']),
            'size' => $this->faker->numberBetween(1000, 8000),
            'facebook_url' => $this->faker->optional()->url(),
            'instagram_url' => $this->faker->optional()->url(),
            'x_url' => $this->faker->optional()->url(),
        ];
    }

    /**
     * Indicate that the branch is for mixed genders.
     */
    public function mixed(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'mix',
        ]);
    }

    /**
     * Indicate that the branch is for women only.
     */
    public function women(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'women',
        ]);
    }

    /**
     * Indicate that the branch is for men only.
     */
    public function men(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'men',
        ]);
    }

    /**
     * Create a branch for a specific site setting.
     */
    public function forSiteSetting(SiteSetting $siteSetting): static
    {
        return $this->state(fn (array $attributes) => [
            'site_setting_id' => $siteSetting->id,
        ]);
    }

    /**
     * Create a branch with a specific manager.
     */
    public function withManager(User $manager): static
    {
        return $this->state(fn (array $attributes) => [
            'manager_id' => $manager->id,
        ]);
    }
}
