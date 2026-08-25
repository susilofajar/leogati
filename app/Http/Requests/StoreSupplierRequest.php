<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->route('supplier')?->id;

        return [
            'name'          => ['required', 'string', 'max:255'],
            'pic_name'      => ['nullable', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'city'          => ['nullable', 'string', 'max:100'],
            'province'      => ['nullable', 'string', 'max:100'],
            'postal_code'   => ['nullable', 'string', 'max:10'],
            'npwp'          => ['nullable', 'string', 'max:30'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'notes'         => ['nullable', 'string'],
            'is_active'     => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'          => 'nama supplier',
            'pic_name'      => 'nama PIC',
            'email'         => 'email',
            'phone'         => 'nomor telepon',
            'address'       => 'alamat',
            'city'          => 'kota',
            'province'      => 'provinsi',
            'postal_code'   => 'kode pos',
            'npwp'          => 'NPWP',
            'payment_terms' => 'syarat pembayaran',
        ];
    }
}
