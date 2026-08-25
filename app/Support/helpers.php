<?php

use App\Support\Formatter;

if (! function_exists('rupiah')) {
    /**
     * Format number to Indonesian Rupiah (Rp).
     */
    function rupiah(int|float $amount, bool $withSymbol = true): string
    {
        return Formatter::rupiah($amount, $withSymbol);
    }
}

if (! function_exists('tgl_indo')) {
    /**
     * Format date to Indonesian localized format.
     */
    function tgl_indo(?string $date, string $format = 'd F Y'): string
    {
        return Formatter::date($date, $format);
    }
}
