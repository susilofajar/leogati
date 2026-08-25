<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public function rules(): array
    {
        $couponId = $this->route('kupon')?->id ?? $this->route('kupon');

        return [
            'code'                => ['required', 'string', 'max:50', 'alpha_num', Rule::unique('coupons', 'code')->ignore($couponId)],
            'name'                => ['required', 'string', 'max:255'],
            'type'                => ['required', 'in:fixed,percent'],
            'value'               => ['required', 'numeric', 'min:1'],
            'min_purchase_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit'         => ['nullable', 'integer', 'min:1'],
            'start_date'          => ['nullable', 'date'],
            'end_date'            => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'           => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'             => 'Kode kupon promo wajib diisi.',
            'code.unique'               => 'Kode kupon promo sudah digunakan.',
            'code.alpha_num'            => 'Kode kupon hanya boleh berupa huruf dan angka tanpa spasi.',
            'name.required'             => 'Nama promo wajib diisi.',
            'type.required'             => 'Tipe kupon wajib dipilih (persen atau nominal tetap).',
            'value.required'            => 'Nilai potongan diskon wajib diisi.',
            'end_date.after_or_equal'   => 'Tanggal berakhir harus sama atau setelah tanggal mulai berlaku.',
        ];
    }
}
