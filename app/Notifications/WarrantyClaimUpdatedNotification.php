<?php

namespace App\Notifications;

use App\Models\WarrantyClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WarrantyClaimUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(public WarrantyClaim $claim, public string $previousStatus)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'submitted' => 'Pengajuan Diterima',
            'reviewing' => 'Sedang Ditinjau Tim Teknis',
            'approved' => 'Klaim Disetujui (Silakan Kirim Unit)',
            'in_repair' => 'Unit Sedang Dalam Perbaikan / RMA',
            'repaired' => 'Unit Telah Berhasil Diperbaiki',
            'replaced' => 'Unit Diganti Baru',
            'rejected' => 'Klaim Ditolak',
            'closed' => 'Klaim Selesai & Ditutup',
        ];

        $statusText = $statusLabels[$this->claim->status] ?? ucfirst($this->claim->status);

        return [
            'type' => 'warranty_claim_updated',
            'title' => "Status Garansi: {$statusText}",
            'message' => "Tiket klaim garansi {$this->claim->claim_number} ({$this->claim->product_name}) telah diperbarui menjadi '{$statusText}'.",
            'url' => route('customer.warranty.show', $this->claim->id),
            'claim_id' => $this->claim->id,
            'claim_number' => $this->claim->claim_number,
            'status' => $this->claim->status,
        ];
    }
}
