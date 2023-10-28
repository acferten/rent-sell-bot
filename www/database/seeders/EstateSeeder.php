<?php

namespace Database\Seeders;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstateStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estates')->insert([
        'user_id' => 1,
        'deal_type' => fake()->randomElement(DealTypes::cases()),
        'video' => null,
        'main_photo' => fake()->filePath(),
        'bedrooms' => fake()->randomNumber(1),
        'status' => fake()->randomElement(EstateStatus::cases()),
        'bathrooms' => fake()->randomNumber(1),
        'house_type_id' => fake()->numberBetween(1, 5),
        'conditioners' => fake()->randomNumber(1),
        'description' => fake()->text(100),
        'latitude' => fake()->randomFloat(2, -90, 90),
        'longitude' => fake()->randomFloat(2, -180, 180),
        'country' => fake()->country,
        'town' => fake()->city,
        'district' => fake()->word,
        'street' => fake()->streetName,
        'house_number' => fake()->buildingNumber,
        'price' => fake()->numberBetween(1000, 100000),
        'views' => 0,
        'chattings' => 0,
        'end_date' => fake()->dateTimeThisYear,
        'created_at' => fake()->dateTimeThisYear,
        'updated_at' => fake()->dateTimeThisYear,
        ]);
    }
}
