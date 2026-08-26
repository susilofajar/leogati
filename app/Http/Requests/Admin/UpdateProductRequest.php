<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('produk');
        $defaultVariant = $product ? $product->variants()->where('is_default', true)->first() : null;
        $variantId = $defaultVariant ? $defaultVariant->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'warranty_period_months' => ['required', 'integer', 'min:0', 'max:120'],
            'status' => ['required', 'in:draft,active,archived'],
            'is_featured' => ['nullable', 'boolean'],

            // Primary Variant
            'sku' => ['required', 'string', 'max:100', Rule::unique('product_variants', 'sku')->ignore($variantId)],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'weight_grams' => ['required', 'integer', 'min:1'],

            // Product Images
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'primary_image_id' => ['nullable', 'integer', 'exists:product_images,id'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama produk',
            'category_id' => 'kategori produk',
            'brand_id' => 'merek produk',
            'short_description' => 'deskripsi singkat',
            'description' => 'deskripsi lengkap',
            'warranty_period_months' => 'masa garansi',
            'status' => 'status produk',
            'sku' => 'SKU produk',
            'price' => 'harga jual',
            'cost_price' => 'harga modal',
            'stock' => 'stok barang',
            'weight_grams' => 'berat produk',
            'images' => 'foto produk',
            'images.*' => 'berkas foto produk',
            'primary_image_id' => 'foto utama produk',
            'delete_images' => 'hapus foto produk',
        ];
    }
}
