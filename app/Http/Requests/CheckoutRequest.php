<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:9', 'max:20'],
            'address_line' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'shipping_courier' => ['required', 'in:jne,sicepat,jnt'],
            'payment_method' => ['required', 'in:bca_va,mandiri_va,bri_va,bni_va,qris,bank_transfer'],
            'notes' => ['nullable', 'string', 'max:500'],
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
            'recipient_name' => 'nama penerima',
            'phone_number' => 'nomor telepon/WhatsApp',
            'address_line' => 'alamat lengkap pengiriman',
            'city' => 'kota/kabupaten',
            'province' => 'provinsi',
            'postal_code' => 'kode pos',
            'shipping_courier' => 'jasa kurir pengiriman',
            'payment_method' => 'metode pembayaran',
            'notes' => 'catatan pesanan',
        ];
    }
}
