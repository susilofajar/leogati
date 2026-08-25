<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SerialNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarrantyApiController extends Controller
{
    /**
     * Cek status dan masa berlaku garansi berdasarkan nomor seri publik.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'serial_number' => ['required', 'string'],
        ]);

        $sn = trim($request->query('serial_number'));

        $serial = SerialNumber::with([
            'productVariant.product.brand',
            'productVariant.product.category',
            'warrantyClaims' => fn($q) => $q->latest('submitted_at'),
        ])->where('serial_number', $sn)->first();

        if (! $serial) {
            return response()->json([
                'status' => 'not_found',
                'message' => "Nomor seri '{$sn}' tidak ditemukan dalam database resmi LEOGATISTORE.",
                'data' => null,
            ], 404);
        }

        $isActive = $serial->warranty_expires_at ? $serial->warranty_expires_at->isFuture() : false;

        return response()->json([
            'status' => 'success',
            'data' => [
                'serial_number' => $serial->serial_number,
                'status' => $serial->status,
                'status_label' => $serial->status_label,
                'product' => [
                    'name' => $serial->productVariant?->product?->name,
                    'variant' => $serial->productVariant?->name,
                    'sku' => $serial->productVariant?->sku,
                    'brand' => $serial->productVariant?->product?->brand?->name,
                    'category' => $serial->productVariant?->product?->category?->name,
                ],
                'purchased_at' => $serial->purchased_at?->toDateString(),
                'warranty_expires_at' => $serial->warranty_expires_at?->toDateString(),
                'is_warranty_active' => $isActive,
                'days_remaining' => $isActive ? (int) now()->diffInDays($serial->warranty_expires_at, false) : 0,
                'has_active_claim' => $serial->hasActiveClaim(),
            ],
        ]);
    }
}
