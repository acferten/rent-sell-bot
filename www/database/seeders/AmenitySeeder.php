<?php

namespace Database\Seeders;

use Domain\Estate\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        Amenity::factory()->count(12)
            ->state(new Sequence(
                ['title' => 'Бассейн'],
                ['title' => 'Закрытый ливинг рум'],
                ['title' => 'Кухонный гарнитур'],
                ['title' => 'Мебель в комнатах'],
                ['title' => 'Холодильник'],
                ['title' => 'Стиральная машина'],
                ['title' => 'Телевизор'],
                ['title' => 'Wi-Fi'],
                ['title' => 'Гараж для машины/байка'],
                ['title' => 'Ванна'],
                ['title' => 'Можно с животными'],
                ['title' => 'Можно с детьми']
            ))->create();
    }
}
