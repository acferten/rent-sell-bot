<?php

namespace Database\Factories\Estate;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Type;
use Domain\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstateFactory extends Factory
{
    protected $model = Estate::class;

    public function definition(): array
    {
        $states = ["Alaska", "Alabama", "Arkansas", "American Samoa", "Arizona", "California", "Colorado", "Connecticut", "District of Columbia", "Delaware", "Florida", "Georgia", "Guam", "Hawaii", "Iowa", "Idaho", "Illinois", "Indiana", "Kansas", "Kentucky", "Louisiana", "Massachusetts", "Maryland", "Maine", "Michigan", "Minnesota", "Missouri", "Mississippi", "Montana", "North Carolina", "North Dakota", "Nebraska", "New Hampshire", "New Jersey", "New Mexico", "Nevada", "New York", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Puerto Rico", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Virginia", "Virgin Islands", "Vermont", "Washington", "Wisconsin", "West Virginia", "Wyoming"];

        return [
            'video' => null,
            'main_photo' => fake()->randomElement(['1.jpg', '2.jpg']),
            'deal_type' => fake()->randomElement(DealTypes::cases()),

            'user_id' => User::all()->random()->id,
            'type_id' => Type::all()->random()->id,

            'bedrooms' => fake()->randomNumber(1),
            'bathrooms' => fake()->randomNumber(1),
            'conditioners' => fake()->randomNumber(1),
            'description' => fake()->realText(100),
            'status' => fake()->randomElement(EstateStatus::cases()),

            'latitude' => fake()->latitude,
            'longitude' => fake()->longitude,
            'country' => fake()->country,
            'town' => fake()->city,
            'state' => fake()->randomElement($states),
            'county' => fake()->realText(15, 1),
            'district' => fake()->word,
            'street' => fake()->streetName,
            'house_number' => fake()->buildingNumber,

            'price' => fake()->numberBetween(1000, 100000),
            'views' => 0,
            'chattings' => 0,

            'end_date' => fake()->dateTimeThisYear,
            'created_at' => fake()->dateTimeThisYear,
            'updated_at' => fake()->dateTimeThisYear,
            'relevance_date' => fake()->dateTime,
        ];
    }
}
