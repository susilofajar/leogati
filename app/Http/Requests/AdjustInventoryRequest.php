<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdjustInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse_id'     => ['required', 'exists:warehouses,id'],
            'quantity_change'  => ['required', 'integer', 'not_in:0'],
            'notes'            => ['required', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse_id'    => 'gudang',
            'quantity_change' => 'perubahan stok',
            'notes'           => 'catatan alasan penyesuaian',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity_change.not_in' => 'Perubahan stok tidak boleh nol.',
        ];
    }
}
