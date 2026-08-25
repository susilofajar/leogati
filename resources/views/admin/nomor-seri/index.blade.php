@extends('layouts.admin')

@section('title', 'Pelacakan Nomor Seri')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">🏷️ Nomor Seri (Serial Numbers)</h1>
            <p class="text-muted mb-0">Pelacakan unit produk terdaftar, status garansi, dan riwayat transaksi.</p>
        </div>
    </div>

    {{-- Filter Pencarian & Status --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.nomor_seri.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Cari Nomor Seri / Nama Produk</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Nomor seri unik atau nama produk...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status Unit</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Direservasi</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Terjual</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="damaged" {{ request('status') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                        <option value="warranty" {{ request('status') == 'warranty' ? 'selected' : '' }}>Klaim Garansi</option>
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
                            <th>Nomor Seri</th>
                            <th>Produk / Varian</th>
                            <th>Lokasi Gudang</th>
                            <th class="text-center">Status</th>
                            <th>Tanggal Beli / PO</th>
                            <th>Garansi Berakhir</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serials as $serial)
                        <tr>
                            <td class="font-monospace fw-bold text-primary">{{ $serial->serial_number }}</td>
                            <td>
                                <div class="fw-semibold">{{ $serial->productVariant->product->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $serial->productVariant->name }} &bull; SKU: {{ $serial->productVariant->sku }}</div>
                            </td>
                            <td class="small">{{ $serial->warehouse->name ?? 'Gudang Pusat' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $serial->status_color }}">
                                    {{ $serial->status_label }}
                                </span>
                            </td>
                            <td class="small text-muted">
                                {{ $serial->purchased_at ? tgl_indo($serial->purchased_at) : '-' }}
                                @if($serial->purchaseOrder)
                                    <div class="small"><a href="{{ route('admin.pembelian.show', $serial->purchaseOrder->id) }}">#{{ $serial->purchaseOrder->po_number }}</a></div>
                                @endif
                            </td>
                            <td class="small">
                                @if($serial->warranty_expires_at)
                                    <span class="{{ $serial->isUnderWarranty() ? 'text-success fw-semibold' : 'text-danger' }}">
                                        {{ tgl_indo($serial->warranty_expires_at) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.nomor_seri.show', $serial->id) }}" class="btn btn-sm btn-outline-primary">
                                    Detail <i class="bi bi-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada nomor seri yang terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($serials->hasPages())
        <div class="card-footer bg-transparent">{{ $serials->links() }}</div>
        @endif
    </div>
</div>
@endsection
