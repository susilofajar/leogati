<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id'               => ['required', 'exists:suppliers,id'],
            'warehouse_id'              => ['required', 'exists:warehouses,id'],
            'expected_at'               => ['nullable', 'date', 'after_or_equal:today'],
            'notes'                     => ['nullable', 'string'],
            'items'                     => ['required', 'array', 'min:1'],
            'items.*.product_variant_id'=> ['required', 'exists:product_variants,id'],
            'items.*.quantity_ordered'  => ['required', 'integer', 'min:1'],
            'items.*.unit_cost'         => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id'               => 'supplier',
            'warehouse_id'              => 'gudang',
            'expected_at'               => 'estimasi tiba',
            'items'                     => 'daftar item',
            'items.*.product_variant_id'=> 'varian produk',
            'items.*.quantity_ordered'  => 'jumlah pesan',
            'items.*.unit_cost'         => 'harga beli per unit',
        ];
    }
}
