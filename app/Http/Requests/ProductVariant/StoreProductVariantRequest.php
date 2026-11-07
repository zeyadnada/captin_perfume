<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'volume' => filled($this->volume) ? number_format((float) $this->volume, 2, '.', '') : null,
            'volume_unit' => Str::lower(trim((string) $this->volume_unit)),
            'sku' => filled($this->sku) ? strtoupper(trim((string) $this->sku)) : null,
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'volume' => [
                'required',
                'numeric',
                'gt:0',
                'max:999999.99',
                Rule::unique('product_variants', 'volume')
                    ->where(fn ($query) => $query
                        ->where('product_id', $product->getKey())
                        ->where('volume_unit', $this->volume_unit)
                        ->where('volume', $this->volume)),
            ],
            'volume_unit' => ['required', Rule::in(['ml', 'oz'])],
            'sku' => ['nullable', 'string', 'max:100', 'alpha_dash', 'unique:product_variants,sku'],
            'price' => ['required', 'decimal:0,2', 'min:0', 'max:9999999999.99'],
            'compare_at_price' => ['nullable', 'decimal:0,2', 'min:0', 'max:9999999999.99', 'gte:price'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
