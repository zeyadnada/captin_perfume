<?php

namespace App\Http\Requests\Brand;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:brands,slug,'.$this->route('brand')->getKey(),
            'description' => 'nullable|string|max:2000',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'required|boolean',
        ];
    }
}
