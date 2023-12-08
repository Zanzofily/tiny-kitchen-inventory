<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface extends RepositoryBaseInterface
{
    public function getById(int $id): ?Product;
}
