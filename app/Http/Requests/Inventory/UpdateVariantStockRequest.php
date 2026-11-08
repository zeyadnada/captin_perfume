<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVariantStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:0'],
            'reserved_quantity' => ['required', 'integer', 'min:0', 'lte:quantity'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
        ];
    }
}
