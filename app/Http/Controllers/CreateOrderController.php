<?php

namespace App\Http\Controllers;

use App\DataObjects\OrderDataObject;
use App\Interfaces\Services\OrderServiceInterface;
use App\Requests\CreateOrderRequest;
use JustSteveKing\DataObjects\Facades\Hydrator;

class CreateOrderController extends Controller
{
    protected OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function __invoke(CreateOrderRequest $request)
    {
        $orderObject = Hydrator::fill(
            class: OrderDataObject::class,
            properties: $request->validated(),
        );

        $order = $this->orderService->createOrder($orderObject);

        // @todo use a dto
        return response()->json($order);
    }
}
