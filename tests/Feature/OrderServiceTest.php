<?php

namespace Tests\Unit;

use App\DataObjects\OrderDataObject;
use App\Exceptions\OutOfStockException;
use App\Interfaces\Repositories\IngredientRepositoryInterface;
use App\Interfaces\Repositories\OrderRepositoryInterface;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Models\Ingredient;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Notification;
use JustSteveKing\DataObjects\Facades\Hydrator;
use Mockery;
use Tests\CreatesApplication;

class OrderServiceTest extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $orderRepositoryMock;

    protected $productRepositoryMock;

    protected $ingredientRepository;

    protected $orderService;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepositoryMock = Mockery::mock(OrderRepositoryInterface::class);
        $this->productRepositoryMock = Mockery::mock(ProductRepositoryInterface::class);
        $this->ingredientRepository = Mockery::mock(IngredientRepositoryInterface::class);

        $this->orderService = new OrderService(
            $this->orderRepositoryMock,
            $this->productRepositoryMock,
            $this->ingredientRepository
        );

    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateOrderWithInsufficientStock()
    {

        $product = Product::find(1);

        $orderDataObject = Hydrator::fill(
            class: OrderDataObject::class,
            properties: [
                'products' => [
                    [
                        'product_id' => 1,
                        'quantity' => 10000,
                    ],
                ],
            ],
        );

        $this->productRepositoryMock
            ->shouldReceive('getById')
            ->once()
            ->with(1)
            ->andReturn($product);

        $this->ingredientRepository
            ->shouldReceive('save')
            ->times(0);

        $this->orderRepositoryMock
            ->shouldReceive('create')
            ->times(0);

        $this->expectException(OutOfStockException::class);
        Notification::fake();
        $this->orderService->createOrder($orderDataObject);
        Notification::assertCount(0);

    }

    public function testCreateOrderWithSufficientStock()
    {
        $product = Product::find(1);

        $orderDataObject = Hydrator::fill(
            class: OrderDataObject::class,
            properties: [
                'products' => [
                    [
                        'product_id' => 1,
                        'quantity' => 1,
                    ],
                ],
            ],
        );

        $this->productRepositoryMock
            ->shouldReceive('getById')
            ->once()
            ->with(1)
            ->andReturn($product);

        $this->ingredientRepository
            ->shouldReceive('save')
            ->times($product->ingredients()->count())
            ->andReturn(true);

        $this->orderRepositoryMock
            ->shouldReceive('create')
            ->with($orderDataObject)
            ->andReturn();

        Notification::fake();
        $this->orderService->createOrder($orderDataObject);
        Notification::assertCount(0);
    }

    public function testCreateOrderWithStockReachingHalf()
    {

        $product = Product::find(1);

        $orderDataObject = Hydrator::fill(
            class: OrderDataObject::class,
            properties: [
                'products' => [
                    [
                        'product_id' => 1,
                        'quantity' => 40,
                    ],
                ],
            ],
        );

        $this->productRepositoryMock
            ->shouldReceive('getById')
            ->once()
            ->with(1)
            ->andReturn($product);

        $this->ingredientRepository
            ->shouldReceive('save')
            ->times($product->ingredients()->count())
            ->andReturn(true);

        $this->orderRepositoryMock
            ->shouldReceive('create')
            ->with($orderDataObject)
            ->andReturn();

        // Notification::assertSentTo(
        //     ['e@e.com' => 'Kitchen Manager'],
        //     LowIngredientInventory::class,
        //     function ($notification) use ($expectedIngredient) {
        //         return $notification->ingredient->id === $expectedIngredient->id;
        //     }
        // );

        Notification::fake();
        $this->orderService->createOrder($orderDataObject);
        Notification::assertCount(1);
    }
}
