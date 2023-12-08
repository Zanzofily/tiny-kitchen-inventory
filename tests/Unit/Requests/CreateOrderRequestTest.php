<?php

namespace Tests\Unit\Models;

use App\Requests\CreateOrderRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CreateOrderRequestTest extends TestCase
{
    public function testCreateRequestWithInvalidMissingData()
    {

        $request = new CreateOrderRequest();

        $validator = Validator::make(['products' => [[]]], $request->rules());

        $this->assertFalse($validator->passes());
    }

    public function testCreateRequestWithValidData()
    {

        $request = new CreateOrderRequest();

        // @todo consider creating a DTO factory
        $validator = Validator::make([
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                ],
            ],
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }
}
