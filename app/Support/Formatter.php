<?php

namespace App\Support;

use Carbon\Carbon;

class Formatter
{
    /**
     * Format number to Indonesian Rupiah (Rp).
     */
    public static function rupiah(int|float $amount, bool $withSymbol = true): string
    {
        $formatted = number_format($amount, 0, ',', '.');
        return $withSymbol ? 'Rp ' . $formatted : $formatted;
    }

    /**
     * Format date to Indonesian localized format (e.g. 19 Agustus 2026).
     */
    public static function date(?string $date, string $format = 'd F Y'): string
    {
        if (! $date) {
            return '-';
        }

        return Carbon::parse($date)->locale('id')->translatedFormat($format);
    }

    /**
     * Format datetime to Indonesian localized format (e.g. 19 Agustus 2026 21:50 WIB).
     */
    public static function datetime(?string $datetime): string
    {
        if (! $datetime) {
            return '-';
        }

        return Carbon::parse($datetime)->locale('id')->translatedFormat('d F Y H:i') . ' WIB';
    }

    /**
     * Get order status badge classes and localized label.
     */
    public static function orderStatus(string $status): array
    {
        $statuses = [
            'pending' => [
                'label' => 'Menunggu Konfirmasi',
                'class' => 'bg-amber-100 text-amber-800 border-amber-200',
            ],
            'awaiting_payment' => [
                'label' => 'Menunggu Pembayaran',
                'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            ],
            'paid' => [
                'label' => 'Pembayaran Diterima',
                'class' => 'bg-blue-100 text-blue-800 border-blue-200',
            ],
            'processing' => [
                'label' => 'Sedang Diproses',
                'class' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            ],
            'packed' => [
                'label' => 'Selesai Dikemas',
                'class' => 'bg-purple-100 text-purple-800 border-purple-200',
            ],
            'shipped' => [
                'label' => 'Sedang Dikirim',
                'class' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
            ],
            'delivered' => [
                'label' => 'Telah Terkirim',
                'class' => 'bg-teal-100 text-teal-800 border-teal-200',
            ],
            'completed' => [
                'label' => 'Selesai',
                'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'class' => 'bg-rose-100 text-rose-800 border-rose-200',
            ],
            'refunded' => [
                'label' => 'Dana Dikembalikan',
                'class' => 'bg-zinc-100 text-zinc-800 border-zinc-200',
            ],
            'returned' => [
                'label' => 'Dikembalikan',
                'class' => 'bg-red-100 text-red-800 border-red-200',
            ],
        ];

        return $statuses[$status] ?? [
            'label' => ucfirst($status),
            'class' => 'bg-slate-100 text-slate-800 border-slate-200',
        ];
    }
}
