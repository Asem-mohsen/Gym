<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'address' => $this->faker->address,
            'password_set_at' => now(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement([0, 1]),
            'is_admin' => 0,
            'email_verified_at' => now(),
            'last_visit_at' => now(),
            'country' => 'Egypt',
            'city' => 'Cairo',
            'remember_token' => Str::random(10),
        ];
    }
}
