<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stock = $this->faker->randomNumber(5);

        return [
            'name' => $this->faker->name(),
            'available_stock' => $stock,
            'original_stock' => $stock,
            'is_stock_monitored' => $this->faker->boolean(),
        ];
    }
}
