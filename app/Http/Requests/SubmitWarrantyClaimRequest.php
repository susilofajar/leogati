<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitWarrantyClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'serial_number'     => ['required', 'string', 'exists:serial_numbers,serial_number'],
            'issue_category'    => ['required', 'string', 'in:dead_on_arrival,defective,malfunction,physical_damage,other'],
            'issue_description' => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'serial_number.required'     => 'Nomor seri produk wajib diisi.',
            'serial_number.exists'       => 'Nomor seri tidak ditemukan dalam basis data LEOGATISTORE.',
            'issue_category.required'    => 'Kategori masalah wajib dipilih.',
            'issue_category.in'          => 'Kategori masalah yang dipilih tidak valid.',
            'issue_description.required' => 'Deskripsi masalah wajib diisi.',
            'issue_description.min'      => 'Deskripsi masalah harus minimal 20 karakter agar dapat diproses.',
            'issue_description.max'      => 'Deskripsi masalah maksimal 2000 karakter.',
        ];
    }
}
