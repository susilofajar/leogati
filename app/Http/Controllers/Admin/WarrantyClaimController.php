<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWarrantyClaimRequest;
use App\Models\WarrantyClaim;
use App\Services\WarrantyClaimService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarrantyClaimController extends Controller
{
    public function __construct(
        protected WarrantyClaimService $warrantyService
    ) {}

    /**
     * Tampilkan daftar seluruh klaim garansi yang masuk ke sistem.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', WarrantyClaim::class);

        $query = WarrantyClaim::with(['serialNumber.productVariant.product', 'customer', 'order'])
            ->latest('submitted_at');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('claim_number', 'like', "%{$search}%")
                  ->orWhereHas('serialNumber', function ($sq) use ($search) {
                      $sq->where('serial_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $claims = $query->paginate(15)->withQueryString();
        $statuses = WarrantyClaim::STATUS_LABELS;

        return view('admin.garansi.index', compact('claims', 'statuses'));
    }

    /**
     * Tampilkan rincian klaim garansi, data unit serial number, dan formulir perubahan status.
     */
    public function show(WarrantyClaim $garansi): View
    {
        $this->authorize('view', $garansi);

        $claim = $garansi->load(['serialNumber.productVariant.product', 'customer', 'order']);
        $statuses = WarrantyClaim::STATUS_LABELS;

        return view('admin.garansi.show', compact('claim', 'statuses'));
    }

    /**
     * Perbarui status dan catatan teknis klaim garansi.
     */
    public function updateStatus(UpdateWarrantyClaimRequest $request, WarrantyClaim $garansi): RedirectResponse
    {
        $this->authorize('update', $garansi);

        $this->warrantyService->updateClaimStatus(
            $garansi,
            $request->input('status'),
            $request->input('admin_notes'),
            $request->input('resolution')
        );

        return redirect()->route('admin.garansi.show', $garansi->id)
            ->with('success', "Status klaim garansi #{$garansi->claim_number} berhasil diperbarui.");
    }
}
