<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::insert([
            [
                'name' => 'Beef',
                'available_stock' => 20000,
                'original_stock' => 20000,
                'is_stock_monitored' => true,
            ],
            [
                'name' => 'Cheese',
                'available_stock' => 5000,
                'original_stock' => 5000,
                'is_stock_monitored' => true,
            ],
            [
                'name' => 'Onion',
                'available_stock' => 1000,
                'original_stock' => 1000,
                'is_stock_monitored' => true,
            ],
        ]);
    }
}
