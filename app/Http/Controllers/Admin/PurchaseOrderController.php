<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiveGoodsRequest;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', PurchaseOrder::class);

        $query = PurchaseOrder::with(['supplier', 'warehouse', 'creator']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('cari')) {
            $query->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $purchaseOrders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.pembelian.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $this->authorize('create', PurchaseOrder::class);

        $suppliers  = Supplier::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $variants   = ProductVariant::with('product')
                                    ->where('is_active', true)
                                    ->get();

        return view('admin.pembelian.create', compact('suppliers', 'warehouses', 'variants'));
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        $this->authorize('create', PurchaseOrder::class);

        $validated = $request->validated();

        $po = PurchaseOrder::create([
            'po_number'    => PurchaseOrder::generatePoNumber(),
            'supplier_id'  => $validated['supplier_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'created_by'   => $request->user()->id,
            'status'       => 'draft',
            'expected_at'  => $validated['expected_at'] ?? null,
            'notes'        => $validated['notes'] ?? null,
        ]);

        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $poItem = PurchaseOrderItem::create([
                'purchase_order_id'  => $po->id,
                'product_variant_id' => $item['product_variant_id'],
                'quantity_ordered'   => $item['quantity_ordered'],
                'quantity_received'  => 0,
                'unit_cost'          => $item['unit_cost'],
            ]);
            $totalAmount += $poItem->subtotal;
        }

        $po->update(['total_amount' => $totalAmount]);

        return redirect()->route('admin.pembelian.show', $po->id)
            ->with('success', "Purchase Order #{$po->po_number} berhasil dibuat.");
    }

    public function show(PurchaseOrder $pembelian)
    {
        $this->authorize('view', $pembelian);

        $pembelian->load(['supplier', 'warehouse', 'creator', 'items.productVariant.product']);

        return view('admin.pembelian.show', compact('pembelian'));
    }

    /**
     * Tandai PO sebagai "Sent" ke supplier.
     */
    public function markSent(PurchaseOrder $pembelian)
    {
        $this->authorize('send', $pembelian);

        if ($pembelian->status !== 'draft') {
            return back()->withErrors(['status' => 'Hanya PO berstatus Draft yang dapat dikirim ke supplier.']);
        }

        $pembelian->update(['status' => 'sent']);

        return back()->with('success', "PO #{$pembelian->po_number} berhasil ditandai sebagai dikirim ke supplier.");
    }

    /**
     * Proses penerimaan barang dari Purchase Order.
     */
    public function receiveGoods(ReceiveGoodsRequest $request, PurchaseOrder $pembelian)
    {
        $this->authorize('receive', $pembelian);

        if (! in_array($pembelian->status, ['sent', 'partial'])) {
            return back()->withErrors(['status' => 'Penerimaan barang hanya dapat dilakukan untuk PO yang telah dikirim ke supplier.']);
        }

        foreach ($request->items as $itemData) {
            $quantityReceived = (int) $itemData['quantity_received'];
            if ($quantityReceived <= 0) {
                continue;
            }

            $poItem = PurchaseOrderItem::findOrFail($itemData['po_item_id']);

            // Parse nomor seri dari textarea (satu per baris)
            $serialNumbers = [];
            if (! empty($itemData['serial_numbers'])) {
                $serialNumbers = array_filter(
                    array_map('trim', explode("\n", $itemData['serial_numbers'])),
                    fn($sn) => $sn !== ''
                );
                $serialNumbers = array_values($serialNumbers);
            }

            $warrantyMonths = (int) ($itemData['warranty_months'] ?? 12);

            try {
                $this->inventoryService->receiveGoods(
                    $poItem,
                    $quantityReceived,
                    $serialNumbers,
                    $warrantyMonths,
                    $request->user()
                );
            } catch (\Illuminate\Validation\ValidationException $e) {
                return back()->withErrors($e->errors())->withInput();
            }
        }

        return redirect()->route('admin.pembelian.show', $pembelian->id)
            ->with('success', 'Penerimaan barang berhasil dicatat. Stok telah diperbarui.');
    }

    /**
     * Batalkan Purchase Order.
     */
    public function cancel(PurchaseOrder $pembelian)
    {
        $this->authorize('cancel', $pembelian);

        if (! in_array($pembelian->status, ['draft', 'sent'])) {
            return back()->withErrors(['status' => 'Hanya PO berstatus Draft atau Dikirim yang dapat dibatalkan.']);
        }

        $pembelian->update(['status' => 'cancelled']);

        return redirect()->route('admin.pembelian.index')
            ->with('success', "PO #{$pembelian->po_number} berhasil dibatalkan.");
    }
}
