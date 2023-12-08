<?php

declare(strict_types=1);

namespace App\DataObjects;

use JustSteveKing\DataObjects\Contracts\DataObjectContract;

readonly class OrderLineDataObject implements DataObjectContract
{
    public function __construct(
        public readonly int $product_id,
        public readonly int $quantity,
    ) {
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
        ];
    }
}
