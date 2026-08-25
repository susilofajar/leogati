@extends('layouts.admin')

@section('title', 'Manajemen Inventaris')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">📦 Inventaris</h1>
            <p class="text-muted mb-0">Daftar stok semua varian produk aktif.</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventaris.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Cari Produk / SKU</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Nama produk atau SKU...">
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="stok_rendah" id="stok_rendah" value="1" class="form-check-input"
                               {{ request('stok_rendah') ? 'checked' : '' }}>
                        <label for="stok_rendah" class="form-check-label">Tampilkan stok rendah saja (&lt; 5)</label>
                    </div>
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
                            <th>Produk / Varian</th>
                            <th>SKU</th>
                            <th class="text-center">Stok (cache)</th>
                            <th class="text-center">Serialized</th>
                            <th class="text-end">Harga Jual</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($variants as $variant)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $variant->product->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $variant->name }}</div>
                            </td>
                            <td class="font-monospace small">{{ $variant->sku }}</td>
                            <td class="text-center">
                                @if($variant->stock <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($variant->stock < 5)
                                    <span class="badge bg-warning text-dark">{{ $variant->stock }}</span>
                                @else
                                    <span class="badge bg-success">{{ number_format($variant->stock) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($variant->is_serialized)
                                    <span class="badge bg-info text-dark">Serial</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold">{{ rupiah($variant->price) }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.inventaris.mutasi', $variant->id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                    Mutasi
                                </a>
                                <a href="{{ route('admin.inventaris.adjust_form', $variant->id) }}" class="btn btn-sm btn-outline-warning">
                                    Sesuaikan
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada data inventaris.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($variants->hasPages())
        <div class="card-footer bg-transparent">{{ $variants->links() }}</div>
        @endif
    </div>
</div>
@endsection
