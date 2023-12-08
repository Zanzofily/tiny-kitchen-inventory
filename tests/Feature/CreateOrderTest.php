<?php

namespace Tests\Unit;

use App\Interfaces\Services\OrderServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;
use Mockery\MockInterface;
use Tests\CreatesApplication;

class CreateOrderTest extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $seed = true;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_purchase_product(): void
    {

        $requestObject = [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 10,
                ],
            ],
        ];
        $this->instance(
            OrderServiceInterface::class,
            Mockery::mock(OrderServiceInterface::class, function (MockInterface $mock) use ($requestObject) {
                $mock
                    ->shouldReceive('createOrder')
                    ->once()
                    ->withArgs(function ($args) use ($requestObject) {
                        return json_encode($args->toArray()) === json_encode($requestObject);
                    })
                    ->andReturn();
            })
        );

        $response = $this->post('/api/order', $requestObject);
        $response->assertStatus(200);
    }
}
