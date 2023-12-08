<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\DataObjects\OrderDataObject;
use App\Models\Order;

interface OrderRepositoryInterface extends RepositoryBaseInterface
{
    public function create(OrderDataObject $orderDataObject): Order;

    public function createLines(Order $order, array $orderLineDataObject): void;
}
