<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => __('admin.validation.images_required'),
            'images.*.required' => __('admin.validation.images_each_required'),
            'images.*.image' => __('admin.validation.images_each_image'),
            'images.*.mimes' => __('admin.validation.images_each_mimes'),
            'images.*.max' => __('admin.validation.images_each_max'),
        ];
    }
}
