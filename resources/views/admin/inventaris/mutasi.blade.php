@extends('layouts.admin')

@section('title', 'Riwayat Mutasi Stok — ' . $varian->sku)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h1 class="h4 mb-0 fw-bold">Riwayat Mutasi Stok</h1>
            <p class="text-muted mb-0">{{ $varian->product->name ?? '' }} — {{ $varian->name }} ({{ $varian->sku }})</p>
        </div>
        <a href="{{ route('admin.inventaris.adjust_form', $varian->id) }}" class="btn btn-warning">
            <i class="bi bi-sliders me-1"></i> Sesuaikan Stok
        </a>
    </div>

    {{-- Stok saat ini --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body">
                    <div class="h2 fw-bold text-primary mb-1">{{ number_format($varian->stock) }}</div>
                    <div class="text-muted small">Stok Saat Ini (Cache)</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body">
                    <div class="h2 fw-bold text-success mb-1">
                        {{ number_format($movements->where('quantity_change', '>', 0)->sum('quantity_change')) }}
                    </div>
                    <div class="text-muted small">Total Masuk (halaman ini)</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body">
                    <div class="h2 fw-bold text-danger mb-1">
                        {{ number_format(abs($movements->where('quantity_change', '<', 0)->sum('quantity_change'))) }}
                    </div>
                    <div class="text-muted small">Total Keluar (halaman ini)</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body">
                    <div class="h2 fw-bold text-info mb-1">{{ $movements->total() }}</div>
                    <div class="text-muted small">Total Transaksi</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Gudang</th>
                            <th class="text-center">Perubahan</th>
                            <th class="text-center">Sebelum</th>
                            <th class="text-center">Sesudah</th>
                            <th>Catatan</th>
                            <th>Dilakukan Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td class="text-muted small">{{ tgl_indo($movement->created_at) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $movement->type_label }}</span>
                            </td>
                            <td class="small">{{ $movement->warehouse->name ?? '-' }}</td>
                            <td class="text-center fw-bold {{ $movement->is_positive ? 'text-success' : 'text-danger' }}">
                                {{ $movement->is_positive ? '+' : '' }}{{ number_format($movement->quantity_change) }}
                            </td>
                            <td class="text-center text-muted">{{ number_format($movement->quantity_before) }}</td>
                            <td class="text-center">{{ number_format($movement->quantity_after) }}</td>
                            <td class="text-muted small" style="max-width:200px;">{{ $movement->notes ?? '-' }}</td>
                            <td class="small">{{ $movement->performer->name ?? 'Sistem' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada riwayat mutasi untuk varian ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($movements->hasPages())
        <div class="card-footer bg-transparent">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection
