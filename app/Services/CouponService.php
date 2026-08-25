<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CouponService
{
    /**
     * Cari dan validasi kupon berdasarkan kodenya.
     *
     * @param  string      $code
     * @param  float|null  $subtotal
     * @return Coupon
     *
     * @throws ValidationException
     */
    public function validateCoupon(string $code, ?float $subtotal = null): Coupon
    {
        $cleanCode = strtoupper(trim($code));

        $coupon = Coupon::where('code', $cleanCode)->first();

        if (! $coupon) {
            throw ValidationException::withMessages([
                'coupon_code' => "Kode kupon '{$cleanCode}' tidak ditemukan atau tidak valid.",
            ]);
        }

        if (! $coupon->is_active) {
            throw ValidationException::withMessages([
                'coupon_code' => "Kupon promo '{$coupon->code}' saat ini tidak aktif.",
            ]);
        }

        $now = Carbon::now();

        if ($coupon->start_date && $now->isBefore($coupon->start_date)) {
            throw ValidationException::withMessages([
                'coupon_code' => "Kupon promo '{$coupon->code}' baru dapat digunakan mulai tanggal " . tgl_indo($coupon->start_date) . ".",
            ]);
        }

        if ($coupon->end_date && $now->isAfter($coupon->end_date)) {
            throw ValidationException::withMessages([
                'coupon_code' => "Kupon promo '{$coupon->code}' telah kedaluwarsa pada " . tgl_indo($coupon->end_date) . ".",
            ]);
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            throw ValidationException::withMessages([
                'coupon_code' => "Kuota pemakaian kupon '{$coupon->code}' telah habis.",
            ]);
        }

        if ($subtotal !== null && $subtotal < $coupon->min_purchase_amount) {
            throw ValidationException::withMessages([
                'coupon_code' => "Kupon '{$coupon->code}' memerlukan minimum belanja sebesar " . rupiah($coupon->min_purchase_amount) . " (Subtotal Anda: " . rupiah($subtotal) . ").",
            ]);
        }

        return $coupon;
    }

    /**
     * Hitung nominal potongan diskon yang didapat.
     */
    public function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        return $coupon->calculateDiscount($subtotal);
    }
}
