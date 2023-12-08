<?php

declare(strict_types=1);

namespace App\Services;

use App\DataObjects\OrderDataObject;
use App\DataObjects\OrderLineDataObject;
use App\Exceptions\OutOfStockException;
use App\Interfaces\Repositories\IngredientRepositoryInterface;
use App\Interfaces\Repositories\OrderRepositoryInterface;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Interfaces\Services\OrderServiceInterface;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\LowIngredientStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OrderService implements OrderServiceInterface
{
    protected OrderRepositoryInterface $orderRepository;

    protected ProductRepositoryInterface $productRepository;

    protected IngredientRepositoryInterface $ingredientRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        IngredientRepositoryInterface $ingredientRepository,
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->ingredientRepository = $ingredientRepository;
    }

    public function createOrder(OrderDataObject $orderDataObject): ?Order
    {
        $order = DB::transaction(function () use ($orderDataObject) {
            foreach ($orderDataObject->products as $orderLine) {
                $this->processOrderLine($orderLine);
            }

            return $this->orderRepository->create($orderDataObject);
        });

        return $order;
    }

    private function processOrderLine(OrderLineDataObject $orderLineDataObject): void
    {
        $product = $this->productRepository->getById($orderLineDataObject->product_id);

        $this->processIngredients(
            $product,
            $orderLineDataObject->quantity
        );
    }

    private function processIngredients(Product $product, int $product_quantity)
    {

        foreach ($product->ingredients as $ingredient) {

            $orderIngredientAmount = $product_quantity * $ingredient->pivot->amount;
            // @dev validate that ingreident has sufficient stock to perform order
            if ($orderIngredientAmount > $ingredient->available_stock) {
                throw new OutOfStockException();
            }

            // @dev deduct from order stock supply
            $ingredient->available_stock -= $orderIngredientAmount;

            // @todo split the logic to prevent notifications on order failure
            if (
                $ingredient->available_stock < $ingredient->supplied_stock * 0.5 &&
                $ingredient->is_stock_monitored
            ) {
                $this->ReportLowIngredientStock($ingredient);
                // @dev disable stock monitoring
                $ingredient->is_stock_monitored = false;
            }

            $this->ingredientRepository->save($ingredient);
        }

    }

    // @todo replace with event
    public function ReportLowIngredientStock(Ingredient $ingredient): void
    {
        Notification::route('mail', [
            'e@e.com' => 'Kitchen Manager',
        ])->notify(new LowIngredientStock($ingredient));
    }
}
