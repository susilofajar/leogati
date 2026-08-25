<?php

namespace App\Http\Requests;

use App\Models\WarrantyClaim;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWarrantyClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya admin/super_admin yang bisa mengubah status klaim
        return auth()->user()?->isAdmin();
    }

    public function rules(): array
    {
        $validStatuses = implode(',', array_keys(WarrantyClaim::STATUS_LABELS));

        return [
            'status'      => ['required', 'string', "in:{$validStatuses}"],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'resolution'  => ['nullable', 'required_if:status,repaired,replaced,rejected,closed', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'      => 'Status klaim wajib dipilih.',
            'status.in'            => 'Status klaim yang dipilih tidak valid.',
            'resolution.required_if' => 'Resolusi wajib diisi saat menyelesaikan atau menolak klaim garansi.',
        ];
    }
}
