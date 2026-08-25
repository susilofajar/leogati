@extends('layouts.admin')

@section('title', 'Manajemen Gudang')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">🏭 Gudang</h1>
            <p class="text-muted mb-0">Kelola lokasi penyimpanan stok produk.</p>
        </div>
    </div>

    {{-- Daftar Gudang --}}
    <div class="row g-4">
        @forelse($warehouses as $warehouse)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <span class="badge bg-secondary mb-1">{{ $warehouse->code }}</span>
                            @if($warehouse->is_default)
                                <span class="badge bg-primary ms-1 mb-1">Utama</span>
                            @endif
                            <h5 class="fw-bold mb-0">{{ $warehouse->name }}</h5>
                        </div>
                        <span class="badge {{ $warehouse->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $warehouse->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    @if($warehouse->address)
                    <p class="text-muted small mb-1">
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $warehouse->address }}, {{ $warehouse->city }}, {{ $warehouse->province }}
                    </p>
                    @endif

                    @if($warehouse->pic_name)
                    <p class="text-muted small mb-1">
                        <i class="bi bi-person me-1"></i> PIC: {{ $warehouse->pic_name }}
                    </p>
                    @endif

                    @if($warehouse->phone)
                    <p class="text-muted small mb-3">
                        <i class="bi bi-telephone me-1"></i> {{ $warehouse->phone }}
                    </p>
                    @endif

                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div>
                            <div class="fw-bold text-primary fs-5">{{ number_format($warehouse->total_skus ?? 0) }}</div>
                            <div class="text-muted small">Total SKU</div>
                        </div>
                        <a href="{{ route('admin.gudang.show', $warehouse) }}" class="btn btn-sm btn-outline-primary">
                            Lihat Stok <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 text-muted">
                <i class="bi bi-building display-4 mb-3 d-block"></i>
                Belum ada gudang yang terdaftar.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
