<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('pesanan') ?? \App\Models\Order::findOrFail($this->route('id')));
    }

    public function rules(): array
    {
        return [
            'status'                   => ['required', 'in:awaiting_payment,paid,processing,packed,shipped,delivered,completed,cancelled,refunded'],
            'payment_status'           => ['required', 'in:unpaid,paid,failed,refunded'],
            'shipping_tracking_number' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'         => 'Status pesanan wajib dipilih.',
            'status.in'               => 'Status pesanan tidak valid.',
            'payment_status.required' => 'Status pembayaran wajib dipilih.',
            'payment_status.in'       => 'Status pembayaran tidak valid.',
        ];
    }
}
