<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\Repositories\IngredientRepositoryInterface;
use App\Models\Ingredient;

class IngredientRepository implements IngredientRepositoryInterface
{
    public function save(Ingredient $ingredient): bool
    {
        return $ingredient->save();
    }
}
