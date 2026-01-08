<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'heveltogap05@gmail.com'],
            [
                'name' => 'togap05_seller',
                'password' => Hash::make('Togap2305'),
                'role' => 'seller',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'abielpasaribu@gmail.com'],
            [
                'name' => 'Abiel_admin',
                'password' => Hash::make('SeongJinWoo999!'),
                'role' => 'seller',
            ]
        );


    }
}
