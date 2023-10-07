<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstateIncludesSeeder extends Seeder
{
    public function run(): void
    {
        $estate_includes = [
            'Бассейн',
            'Гараж для машины/мотоцикла',
            'Крышная терраса',
            'Закрытая жилая комната',
            'Кухонный гарнитур',
            'Холодильник',
            'Стиральная машина',
            'Духовка',
            'Посудомоечная машина',
            'Телевизор',
            'Кондиционер',
            'Wi-Fi',
            'Ванна',
            'Душевая кабина',
            'Можно с животными',
            'Можно с детьми'
        ];

        foreach ($estate_includes as $estate_include) {
            DB::table('includes')->insert([
                'title' => $estate_include,
            ]);
        }
    }
}
