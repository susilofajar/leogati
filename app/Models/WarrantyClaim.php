<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarrantyClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_number',
        'serial_number_id',
        'customer_id',
        'order_id',
        'issue_category',
        'issue_description',
        'status',
        'admin_notes',
        'resolution',
        'submitted_at',
        'reviewed_at',
        'resolved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
        'resolved_at'  => 'datetime',
    ];

    /* ─── Label Bahasa Indonesia ─── */

    public const STATUS_LABELS = [
        'submitted'  => 'Diajukan',
        'reviewing'  => 'Sedang Ditinjau',
        'approved'   => 'Disetujui',
        'in_repair'  => 'Sedang Diperbaiki',
        'repaired'   => 'Selesai Diperbaiki',
        'replaced'   => 'Diganti Unit Baru',
        'rejected'   => 'Ditolak',
        'closed'     => 'Ditutup',
    ];

    public const STATUS_COLORS = [
        'submitted'  => 'warning',
        'reviewing'  => 'info',
        'approved'   => 'primary',
        'in_repair'  => 'info',
        'repaired'   => 'success',
        'replaced'   => 'success',
        'rejected'   => 'danger',
        'closed'     => 'secondary',
    ];

    public const ISSUE_CATEGORY_LABELS = [
        'dead_on_arrival' => 'Mati Total Saat Diterima (DOA)',
        'defective'       => 'Cacat Produksi / Komponen Rusak',
        'malfunction'     => 'Kerusakan Fungsi (Pemakaian Normal)',
        'physical_damage' => 'Kerusakan Fisik',
        'other'           => 'Masalah Lainnya',
    ];

    /**
     * Status yang dianggap "terminal" / sudah selesai.
     */
    public const TERMINAL_STATUSES = ['repaired', 'replaced', 'rejected', 'closed'];

    /* ─── Relationships ─── */

    public function serialNumber(): BelongsTo
    {
        return $this->belongsTo(SerialNumber::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /* ─── Accessors ─── */

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function getIssueCategoryLabelAttribute(): string
    {
        return self::ISSUE_CATEGORY_LABELS[$this->issue_category] ?? $this->issue_category;
    }

    public function isTerminal(): bool
    {
        return in_array($this->status, self::TERMINAL_STATUSES);
    }

    /* ─── Claim Number Generator ─── */

    public static function generateClaimNumber(): string
    {
        $prefix = 'WC-' . now()->format('Ymd') . '-';

        $latest = static::where('claim_number', 'like', $prefix . '%')
            ->orderByDesc('claim_number')
            ->value('claim_number');

        if ($latest) {
            $lastSeq = (int) substr($latest, -4);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        return $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }
}
