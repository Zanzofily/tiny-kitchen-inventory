<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getById(int $id): ?Product
    {
        return Product::find($id);
    }
}
