<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LockerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'locker_number' => $this->faker->randomFloat(2,5,1000),
            'password' => bcrypt('password'),
            'is_locked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
