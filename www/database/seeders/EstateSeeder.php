<?php

namespace Database\Seeders;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\Amenity;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Price;
use Illuminate\Database\Seeder;

class EstateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Estate::factory()->count(50)->create();

        Estate::all()->each(function ($estate) {
            $estateIncludes = Amenity::all()->random(3);
            $estate->includes()->attach($estateIncludes);
        });

        Estate::where('deal_type', DealTypes::rent->value)->get()
            ->each(fn($estate) => $estate->prices()
                ->save(new Price(['price' => fake()->numberBetween(1000, 100000),
                    'period' => fake()->randomElement(EstatePeriods::cases())]))
            );

    }
}
