@extends('layouts.admin')

@section('title', 'Purchase Order (Pembelian)')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">📋 Pembelian (Purchase Orders)</h1>
            <p class="text-muted mb-0">Kelola pesanan pembelian ke supplier dan proses penerimaan barang.</p>
        </div>
        <a href="{{ route('admin.pembelian.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Buat PO Baru
        </a>
    </div>

    {{-- Filter Status & Pencarian --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pembelian.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Cari Nomor PO / Supplier</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="PO-20260819-... atau nama supplier">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status PO</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Dikirim ke Supplier</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Diterima Sebagian</option>
                        <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Diterima Lengkap</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor PO</th>
                            <th>Supplier</th>
                            <th>Gudang Tujuan</th>
                            <th>Tanggal Dibuat</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Total Biaya</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchaseOrders as $po)
                        <tr>
                            <td class="font-monospace fw-bold text-primary">{{ $po->po_number }}</td>
                            <td>
                                <div class="fw-semibold">{{ $po->supplier->name ?? '-' }}</div>
                                <div class="text-muted small">PIC: {{ $po->supplier->pic_name ?? '-' }}</div>
                            </td>
                            <td>{{ $po->warehouse->name ?? '-' }}</td>
                            <td class="small text-muted">{{ tgl_indo($po->created_at) }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $po->status_color }} px-2 py-1">
                                    {{ $po->status_label }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">{{ rupiah($po->total_amount) }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.pembelian.show', $po->id) }}" class="btn btn-sm btn-outline-primary">
                                    Detail <i class="bi bi-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada Purchase Order.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($purchaseOrders->hasPages())
        <div class="card-footer bg-transparent">{{ $purchaseOrders->links() }}</div>
        @endif
    </div>
</div>
@endsection
