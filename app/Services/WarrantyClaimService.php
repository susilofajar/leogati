<?php

namespace App\Services;

use App\Models\SerialNumber;
use App\Models\User;
use App\Models\WarrantyClaim;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WarrantyClaimService
{
    /**
     * Ajukan klaim garansi baru.
     *
     * Validasi:
     * - Serial number milik pelanggan ini (customer_id)
     * - Garansi masih aktif (warranty_expires_at belum lewat)
     * - Tidak ada klaim aktif lain untuk serial ini
     *
     * @param  SerialNumber  $serial
     * @param  User          $customer
     * @param  array         $data  [issue_category, issue_description]
     * @return WarrantyClaim
     *
     * @throws ValidationException
     */
    public function submitClaim(SerialNumber $serial, User $customer, array $data): WarrantyClaim
    {
        return DB::transaction(function () use ($serial, $customer, $data) {
            // 1. Validasi kepemilikan
            if ($serial->customer_id !== $customer->id) {
                throw ValidationException::withMessages([
                    'serial_number' => 'Nomor seri ini tidak terdaftar atas nama akun Anda. Pastikan Anda menggunakan akun yang sama saat melakukan pembelian.',
                ]);
            }

            // 2. Validasi status serial (harus 'sold' untuk klaim)
            if (! in_array($serial->status, ['sold', 'warranty'])) {
                throw ValidationException::withMessages([
                    'serial_number' => 'Unit dengan nomor seri ini tidak dalam status yang dapat diklaim garansi (status saat ini: ' . ($serial->status_label) . ').',
                ]);
            }

            // 3. Validasi garansi masih aktif
            if ($serial->warranty_expires_at && $serial->warranty_expires_at->isPast()) {
                throw ValidationException::withMessages([
                    'serial_number' => 'Masa garansi untuk unit ini telah berakhir pada ' . tgl_indo($serial->warranty_expires_at) . '. Klaim garansi tidak dapat diproses.',
                ]);
            }

            // 4. Validasi tidak ada klaim aktif
            if ($serial->hasActiveClaim()) {
                throw ValidationException::withMessages([
                    'serial_number' => 'Sudah ada klaim garansi aktif untuk nomor seri ini. Silakan tunggu hingga klaim sebelumnya selesai diproses.',
                ]);
            }

            // 5. Buat klaim
            $claim = WarrantyClaim::create([
                'claim_number'      => WarrantyClaim::generateClaimNumber(),
                'serial_number_id'  => $serial->id,
                'customer_id'       => $customer->id,
                'order_id'          => $serial->orderItem?->order_id,
                'issue_category'    => $data['issue_category'],
                'issue_description' => $data['issue_description'],
                'status'            => 'submitted',
                'submitted_at'      => Carbon::now(),
            ]);

            // 6. Ubah status serial menjadi 'warranty'
            $serial->update(['status' => 'warranty']);

            return $claim;
        });
    }

    /**
     * Admin mengubah status klaim garansi.
     *
     * @param  WarrantyClaim  $claim
     * @param  string         $newStatus
     * @param  string|null    $adminNotes
     * @param  string|null    $resolution   (wajib jika status terminal)
     * @return WarrantyClaim
     *
     * @throws ValidationException
     */
    public function updateClaimStatus(
        WarrantyClaim $claim,
        string $newStatus,
        ?string $adminNotes = null,
        ?string $resolution = null
    ): WarrantyClaim {
        return DB::transaction(function () use ($claim, $newStatus, $adminNotes, $resolution) {
            // Validasi klaim belum terminal
            if ($claim->isTerminal()) {
                throw ValidationException::withMessages([
                    'status' => 'Klaim garansi ini sudah dalam status akhir (' . $claim->status_label . ') dan tidak dapat diubah lagi.',
                ]);
            }

            $updateData = ['status' => $newStatus];

            // Catatan admin
            if ($adminNotes !== null) {
                $updateData['admin_notes'] = $adminNotes;
            }

            // Timestamp reviewing
            if ($newStatus === 'reviewing' && ! $claim->reviewed_at) {
                $updateData['reviewed_at'] = Carbon::now();
            }

            // Status terminal → simpan resolusi & resolved_at
            if (in_array($newStatus, WarrantyClaim::TERMINAL_STATUSES)) {
                $updateData['resolved_at'] = Carbon::now();

                if ($resolution) {
                    $updateData['resolution'] = $resolution;
                }

                // Kembalikan status serial number
                $this->restoreSerialStatus($claim, $newStatus);
            }

            $previousStatus = $claim->status;
            $claim->update($updateData);

            // Notify customer
            try {
                if ($claim->customer) {
                    $claim->customer->notify(new \App\Notifications\WarrantyClaimUpdatedNotification($claim, $previousStatus));
                }
            } catch (\Throwable $e) {
                // Ignore notification failure
            }

            // Record Audit Log
            \App\Services\AuditLogService::log(
                action: 'warranty_claim_updated',
                targetType: 'WarrantyClaim',
                targetId: $claim->id,
                payload: [
                    'claim_number' => $claim->claim_number,
                    'previous_status' => $previousStatus,
                    'new_status' => $newStatus,
                    'admin_notes' => $adminNotes,
                    'resolution' => $resolution,
                ]
            );

            return $claim->fresh();
        });
    }

    /**
     * Kembalikan status serial number setelah klaim selesai.
     */
    protected function restoreSerialStatus(WarrantyClaim $claim, string $claimStatus): void
    {
        $serial = $claim->serialNumber;

        if (! $serial) {
            return;
        }

        switch ($claimStatus) {
            case 'repaired':
                // Unit diperbaiki → kembali ke pelanggan
                $serial->update(['status' => 'sold']);
                break;

            case 'replaced':
                // Unit diganti baru → unit lama jadi 'damaged', unit baru tercatat terpisah
                $serial->update(['status' => 'damaged']);
                break;

            case 'rejected':
            case 'closed':
                // Klaim ditolak/ditutup → kembalikan ke 'sold'
                $serial->update(['status' => 'sold']);
                break;
        }
    }
}
