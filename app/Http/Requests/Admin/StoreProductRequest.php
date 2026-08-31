<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'sku' => ['required', 'string', 'max:100', 'unique:product_variants,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'weight_grams' => ['required', 'integer', 'min:1'],

            // Product Images & Video
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
            'video' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov', 'max:51200'],
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
            'primary_image_index' => 'foto utama produk',
            'video' => 'video produk',
        ];
    }
}
