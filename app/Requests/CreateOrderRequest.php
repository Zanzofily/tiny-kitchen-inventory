<?php

declare(strict_types=1);

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
