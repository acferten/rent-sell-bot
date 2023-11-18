<?php

namespace Database\Factories\Shared;

use Domain\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'phone' => fake()->unique()->phoneNumber(),
            'telegram_id' => fake()->numberBetween(1, 100),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
        ];
    }
}
