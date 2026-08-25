@extends('layouts.admin')

@section('title', 'Detail Stok — ' . $gudang->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.gudang.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold">{{ $gudang->name }}</h1>
            <p class="text-muted mb-0">{{ $gudang->code }} &bull; {{ $gudang->city }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk / SKU</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Direservasi</th>
                            <th class="text-center">Tersedia</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryItems as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->productVariant->product->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $item->productVariant->name }} &bull; SKU: {{ $item->productVariant->sku }}</div>
                            </td>
                            <td class="text-center fw-bold">{{ number_format($item->quantity) }}</td>
                            <td class="text-center text-warning">{{ number_format($item->reserved_quantity) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $item->available_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ number_format($item->available_quantity) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.inventaris.mutasi', $item->productVariant->id) }}" class="btn btn-sm btn-outline-primary">
                                    Mutasi
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada stok di gudang ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($inventoryItems->hasPages())
        <div class="card-footer bg-transparent">{{ $inventoryItems->links() }}</div>
        @endif
    </div>
</div>
@endsection
