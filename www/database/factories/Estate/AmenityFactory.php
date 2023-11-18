<?php

namespace Database\Factories\Estate;


use Domain\Estate\Models\Amenity;

use Illuminate\Database\Eloquent\Factories\Factory;

class AmenityFactory extends Factory
{
    protected $model = Amenity::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word
        ];
    }
}
