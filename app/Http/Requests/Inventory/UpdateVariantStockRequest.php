<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateVariantStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reserved_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $variant = $this->route('variant');
                $quantity = $variant?->stock()->value('quantity') ?? 0;

                if ((int) $this->input('reserved_quantity', 0) > $quantity) {
                    $validator->errors()->add(
                        'reserved_quantity',
                        __('admin.validation.reserved_exceeds_physical')
                    );
                }
            },
        ];
    }
}
