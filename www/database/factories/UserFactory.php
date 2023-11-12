<?php

namespace Database\Factories;

use Domain\Shared\Models\Actor\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'phone' => fake()->unique()->phoneNumber(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
        ];
    }
}
