<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductIngredientSeeder extends Seeder
{
    public function run()
    {
        $burger = Product::first();

        $burger->ingredients()->attach(
            Ingredient::where('name', 'Beef')->first()->id,
            ['amount' => 150]
        );

        $burger->ingredients()->attach(
            Ingredient::where('name', 'Cheese')->first()->id,
            ['amount' => 30]
        );

        $burger->ingredients()->attach(
            Ingredient::where('name', 'Onion')->first()->id,
            ['amount' => 20]
        );

    }
}
