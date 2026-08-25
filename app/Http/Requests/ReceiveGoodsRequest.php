<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiveGoodsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items'                       => ['required', 'array', 'min:1'],
            'items.*.po_item_id'          => ['required', 'exists:purchase_order_items,id'],
            'items.*.quantity_received'   => ['required', 'integer', 'min:0'],
            'items.*.serial_numbers'      => ['nullable', 'string'],
            'items.*.warranty_months'     => ['nullable', 'integer', 'min:0', 'max:120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'items'                       => 'daftar item',
            'items.*.quantity_received'   => 'jumlah diterima',
            'items.*.serial_numbers'      => 'nomor seri',
            'items.*.warranty_months'     => 'masa garansi (bulan)',
        ];
    }
}
