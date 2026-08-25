<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitWarrantyClaimRequest;
use App\Models\SerialNumber;
use App\Models\WarrantyClaim;
use App\Services\WarrantyClaimService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    public function __construct(
        protected WarrantyClaimService $warrantyService
    ) {}

    /**
     * Display the warranty lookup page.
     */
    public function check(Request $request): View
    {
        $rawSn = $request->query('sn');
        $serialNumber = $rawSn ? strtoupper(trim($rawSn)) : null;
        $result = null;

        if ($serialNumber) {
            $serial = SerialNumber::with(['productVariant.product'])
                ->where('serial_number', $serialNumber)
                ->first();

            if ($serial) {
                $result = [
                    'searched'      => true,
                    'serial_number' => $serial->serial_number,
                    'found'         => true,
                    'product_name'  => ($serial->productVariant->product->name ?? '') . ' - ' . ($serial->productVariant->name ?? ''),
                    'purchase_date' => $serial->sold_at ? tgl_indo($serial->sold_at) : ($serial->purchased_at ? tgl_indo($serial->purchased_at) : '-'),
                    'warranty_end'  => $serial->warranty_expires_at ? tgl_indo($serial->warranty_expires_at) : 'Seumur Hidup (Lifetime)',
                    'status'        => $serial->status_label,
                    'is_active'     => $serial->warranty_expires_at ? $serial->warranty_expires_at->isFuture() : true,
                    'can_claim'     => $serial->isUnderWarranty() && ! $serial->hasActiveClaim(),
                ];
            } else {
                $result = [
                    'searched'      => true,
                    'serial_number' => $serialNumber,
                    'found'         => false,
                    'message'       => 'Nomor seri tidak ditemukan dalam basis data garansi resmi LEOGATISTORE. Pastikan nomor seri yang dimasukkan sudah sesuai dengan yang tertera pada stiker produk atau faktur pembelian.',
                ];
            }
        }

        return view('warranty.check', compact('result', 'serialNumber'));
    }

    /**
     * Tampilkan formulir pengajuan klaim garansi untuk pelanggan.
     */
    public function claimForm(Request $request): View
    {
        $prefilledSn = $request->query('sn');
        $serial = null;

        if ($prefilledSn) {
            $serial = SerialNumber::with(['productVariant.product'])
                ->where('serial_number', strtoupper(trim($prefilledSn)))
                ->first();
        }

        $categories = WarrantyClaim::ISSUE_CATEGORY_LABELS;

        return view('warranty.claim', compact('serial', 'prefilledSn', 'categories'));
    }

    /**
     * Proses pengajuan klaim garansi dari pelanggan.
     */
    public function submitClaim(SubmitWarrantyClaimRequest $request): RedirectResponse
    {
        $serial = SerialNumber::where('serial_number', strtoupper(trim($request->serial_number)))->firstOrFail();

        $claim = $this->warrantyService->submitClaim(
            $serial,
            auth()->user(),
            $request->validated()
        );

        return redirect()->route('customer.warranty.show', $claim->claim_number)
            ->with('success', "Klaim garansi Anda (#{$claim->claim_number}) berhasil diajukan dan sedang dalam antrean peninjauan oleh tim teknis kami.");
    }

    /**
     * Daftar riwayat klaim garansi milik pelanggan yang sedang login.
     */
    public function myClaims(): View
    {
        $claims = WarrantyClaim::with(['serialNumber.productVariant.product', 'order'])
            ->where('customer_id', auth()->id())
            ->latest('submitted_at')
            ->paginate(10);

        return view('customer.warranty.index', compact('claims'));
    }

    /**
     * Rincian status dan timeline klaim garansi pelanggan.
     */
    public function showClaim(string $claimNumber): View
    {
        $claim = WarrantyClaim::with(['serialNumber.productVariant.product', 'order', 'customer'])
            ->where('claim_number', $claimNumber)
            ->firstOrFail();

        // Otorisasi: hanya pemilik atau admin
        if ($claim->customer_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke klaim garansi ini.');
        }

        return view('customer.warranty.show', compact('claim'));
    }
}

