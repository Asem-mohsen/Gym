<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'gym_name' => [
                'en' => $this->faker->company() . ' Gym',
                'ar' => 'صالة رياضية ' . $this->faker->company()
            ],
            'size' => $this->faker->numberBetween(1000, 10000),
            'slug' => $this->faker->slug(),
            'address' => [
                'en' => $this->faker->address(),
                'ar' => $this->faker->address()
            ],
            'description' => [
                'en' => $this->faker->paragraph(),
                'ar' => $this->faker->paragraph()
            ],
            'contact_email' => $this->faker->safeEmail(),
            'site_url' => $this->faker->url(),
        ];
    }
}
