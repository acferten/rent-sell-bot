<?php

namespace Database\Seeders;

use Domain\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(2)
            ->state(new Sequence([
                'id' => 472041603,
                'is_admin' => 1,
                'username' => 'grepnam3',
                'first_name' => "/////////",
                'password' => Hash::make('password')],

                ['id' => 415670490,
                    'username' => 'Silvery11',
                    'first_name' => 'Кирилл']
            ))
            ->create();
    }
}
