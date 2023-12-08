<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class OutOfStockException extends Exception
{
    protected $message = 'Product is out of stock.';

    /**
     * Get the exception's context information.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return [];
    }

    // @todo use more unified error rendering and handling
    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], 400);
    }
}
