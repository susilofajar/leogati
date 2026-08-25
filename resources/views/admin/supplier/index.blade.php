@extends('layouts.admin')

@section('title', 'Manajemen Supplier')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">🤝 Supplier (Pemasok)</h1>
            <p class="text-muted mb-0">Daftar vendor dan distributor resmi mitra LEOGATISTORE.</p>
        </div>
        <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Supplier
        </a>
    </div>

    {{-- Filter & Pencarian --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.supplier.index') }}" class="row g-2 align-items-end">
                <div class="col-md-9">
                    <label class="form-label small fw-semibold">Cari Supplier</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Nama supplier, kode, email...">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Cari
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
                            <th>Kode</th>
                            <th>Nama Perusahaan</th>
                            <th>PIC / Kontak</th>
                            <th>Kota</th>
                            <th>Syarat Bayar</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $sup)
                        <tr>
                            <td class="font-monospace fw-bold text-primary">{{ $sup->code }}</td>
                            <td>
                                <div class="fw-semibold">{{ $sup->name }}</div>
                                @if($sup->email)<div class="text-muted small">{{ $sup->email }}</div>@endif
                            </td>
                            <td>
                                <div>{{ $sup->pic_name ?? '-' }}</div>
                                @if($sup->phone)<div class="text-muted small">{{ $sup->phone }}</div>@endif
                            </td>
                            <td>{{ $sup->city ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $sup->payment_terms ?? 'NET30' }}</span></td>
                            <td class="text-center">
                                <span class="badge {{ $sup->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $sup->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.supplier.show', $sup) }}" class="btn btn-sm btn-outline-secondary me-1">
                                    Detail
                                </a>
                                <a href="{{ route('admin.supplier.edit', $sup) }}" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada supplier yang terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($suppliers->hasPages())
        <div class="card-footer bg-transparent">{{ $suppliers->links() }}</div>
        @endif
    </div>
</div>
@endsection
