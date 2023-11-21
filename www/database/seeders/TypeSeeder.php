<?php

namespace Database\Seeders;

use Domain\Estate\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        Type::factory()->count(3)
            ->state(new Sequence(
                ['title' => 'Вилла/дом'],
                ['title' => 'Апартаменты/квартира'],
                ['title' => 'Комната'],
            ))->create();
    }
}
