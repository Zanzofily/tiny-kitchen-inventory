<?php

declare(strict_types=1);

namespace App\DataObjects;

use EventSauce\ObjectHydrator\PropertyCasters\CastListToType;
use JustSteveKing\DataObjects\Contracts\DataObjectContract;

readonly class OrderDataObject implements DataObjectContract
{
    public function __construct(
        #[CastListToType(OrderLineDataObject::class)]
        public readonly array $products,
    ) {
    }

    public function toArray(): array
    {
        return [
            'products' => $this->products,
        ];
    }
}
