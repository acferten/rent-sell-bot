<?php

namespace Database\Seeders;

use Domain\Estate\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        Amenity::factory()->count(16)
            ->state(new Sequence(
                ['title' => 'Бассейн'],
                ['title' => 'Гараж для машины/мотоцикла'],
                ['title' => 'Крышная терраса'],
                ['title' => 'Закрытая жилая комната'],
                ['title' => 'Кухонный гарнитур'],
                ['title' => 'Холодильник'],
                ['title' => 'Стиральная машина'],
                ['title' => 'Духовка'],
                ['title' => 'Посудомоечная машина'],
                ['title' => 'Телевизор'],
                ['title' => 'Кондиционер'],
                ['title' => 'Wi-Fi'],
                ['title' => 'Ванна'],
                ['title' => 'Душевая кабина'],
                ['title' => 'Можно с животными'],
                ['title' => 'Можно с детьми']
            ))->create();
    }
}
