<?php

namespace Tests\Unit\Models;

use App\Models\Ingredient;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    public function test_contains_valid_fillable_properties(): void
    {
        $in = new Ingredient();
        $this->assertEquals(['available_stock', 'original_stock', 'is_stock_monitored'], $in->getFillable());
    }
}
