<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstateTypesSeeder extends Seeder
{
    public function run(): void
    {
        $estate_types = [
            'Вилла',
            'Дом',
            'Апартаменты',
            'Квартира',
            'Пентхаус'
        ];

        foreach ($estate_types as $estate_type) {
            DB::table('house_types')->insert([
                'title' => $estate_type,
                'created_at' => now(),
            ]);
        }
    }
}
