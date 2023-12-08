<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DataObjects\OrderDataObject;
use App\DataObjects\OrderLineDataObject;
use App\Interfaces\Repositories\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(OrderDataObject $orderDataObject): Order
    {
        $order = Order::create();
        $this->createLines($order, $orderDataObject->products);

        return $order;
    }

    /**
     * @todo consider improving types and adding a mapper
     */
    public function createLines(Order $order, array $orderLineDataObject): void
    {
        $orderLinesArray = array_map(
            fn (OrderLineDataObject $orderLine) => $orderLine->toArray(),
            $orderLineDataObject
        );
        $order->products()->attach($orderLinesArray);
    }
}
