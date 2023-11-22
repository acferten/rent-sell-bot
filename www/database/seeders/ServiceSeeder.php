<?php

namespace Database\Seeders;

use Domain\Estate\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::factory()->count(8)
            ->state(new Sequence(
                ['title' => 'Электричество'],
                ['title' => 'Интернет'],
                ['title' => 'Уборка'],
                ['title' => 'Чистка бассейна'],
                ['title' => 'Газ'],
                ['title' => 'Смена постельного белья'],
                ['title' => 'Телевизор'],
                ['title' => 'Завтрак']
            ))->create();
    }
}
