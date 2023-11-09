<?php

namespace Database\Seeders;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateInclude;
use Domain\Estate\Models\EstatePrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = ["Alaska", "Alabama", "Arkansas", "American Samoa", "Arizona", "California", "Colorado", "Connecticut", "District of Columbia", "Delaware", "Florida", "Georgia", "Guam", "Hawaii", "Iowa", "Idaho", "Illinois", "Indiana", "Kansas", "Kentucky", "Louisiana", "Massachusetts", "Maryland", "Maine", "Michigan", "Minnesota", "Missouri", "Mississippi", "Montana", "North Carolina", "North Dakota", "Nebraska", "New Hampshire", "New Jersey", "New Mexico", "Nevada", "New York", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Puerto Rico", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Virginia", "Virgin Islands", "Vermont", "Washington", "Wisconsin", "West Virginia", "Wyoming"];

        for ($i = 0; $i < 100; $i++) {
            DB::table('estates')->insert([
                'user_id' => 1,
                'deal_type' => fake()->randomElement(DealTypes::cases()),
                'video' => null,
                'main_photo' => fake()->randomElement(['room1.jpg', 'room2.jpg']),
                'bedrooms' => fake()->randomNumber(1),
                'status' => 'Активно',
                'bathrooms' => fake()->randomNumber(1),
                'house_type_id' => fake()->numberBetween(1, 5),
                'conditioners' => fake()->randomNumber(1),
                'description' => fake()->realText(100),
                'latitude' => fake()->randomFloat(2, -90, 90),
                'longitude' => fake()->randomFloat(2, -180, 180),
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
            ]);
        }

        Estate::all()->each(function ($estate) {
            $estateIncludes = EstateInclude::all()->random(3);
            $estate->includes()->attach($estateIncludes);
        });

        Estate::where('deal_type', DealTypes::rent->value)->get()
            ->each(fn($estate) => $estate->prices()
                ->save(new EstatePrice(['price' => fake()->numberBetween(1000, 100000),
                    'period' => fake()->randomElement(EstatePeriods::cases())]))
            );

    }
}
