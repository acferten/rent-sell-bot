<?php

namespace Database\Factories\Estate;

use Domain\Estate\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeFactory extends Factory
{
    protected $model = Type::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word
        ];
    }
}
