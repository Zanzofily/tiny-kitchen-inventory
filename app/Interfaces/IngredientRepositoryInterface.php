<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Ingredient;

interface IngredientRepositoryInterface extends RepositoryBaseInterface
{
    public function save(Ingredient $ingredient): bool;
}
