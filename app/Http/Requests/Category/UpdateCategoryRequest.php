<?php

namespace App\Http\Requests\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => filled($this->slug) ? Str::slug($this->slug) : Str::slug($this->name ?? ''),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'parent_id' => 'nullable|integer|exists:categories,id|different:'.$category->getKey(),
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:categories,slug,'.$category->getKey(),
            'description' => 'nullable|string|max:2000',
            'is_active' => 'required|boolean',
        ];
    }
}
