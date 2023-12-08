<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\DataObjects\OrderDataObject;
use App\Models\Ingredient;
use App\Models\Order;

interface OrderServiceInterface
{
    public function createOrder(OrderDataObject $orderDataObject): ?Order;

    public function ReportLowIngredientInventory(Ingredient $ingredient): void;
}
