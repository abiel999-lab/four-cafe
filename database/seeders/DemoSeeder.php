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
            ['email' => 'seller@four.local'],
            [
                'name' => 'Seller FOUR',
                'password' => Hash::make('password'),
                'role' => 'seller',
            ]
        );

        $coffee = Category::query()->firstOrCreate(['name' => 'Coffee'], ['sort_order' => 1]);
        $snack  = Category::query()->firstOrCreate(['name' => 'Snacks'], ['sort_order' => 2]);

        $shot = Option::query()->firstOrCreate(['name' => 'Extra Shot'], ['price' => 8000, 'is_active' => true]);
        $caramel = Option::query()->firstOrCreate(['name' => 'Caramel Syrup'], ['price' => 5000, 'is_active' => true]);

        $p1 = Product::query()->firstOrCreate(
            ['name' => 'Americano', 'category_id' => $coffee->id],
            ['description' => 'Kopi hitam', 'price' => 20000, 'stock' => 20, 'is_available' => true, 'sort_order' => 1]
        );
        $p2 = Product::query()->firstOrCreate(
            ['name' => 'Croissant', 'category_id' => $snack->id],
            ['description' => 'Snack', 'price' => 18000, 'stock' => 15, 'is_available' => true, 'sort_order' => 1]
        );

        $p1->options()->syncWithoutDetaching([$shot->id, $caramel->id]);
    }
}
