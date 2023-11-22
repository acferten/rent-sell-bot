<?php

namespace Database\Factories\Estate;

use Domain\Estate\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word
        ];
    }
}
