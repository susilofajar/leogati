@extends('layouts.admin')

@section('title', 'Detail Supplier — ' . $supplier->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.supplier.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h1 class="h3 mb-0 fw-bold">{{ $supplier->name }}</h1>
            <p class="text-muted mb-0">Kode: <span class="font-monospace fw-bold text-primary">{{ $supplier->code }}</span></p>
        </div>
        <a href="{{ route('admin.supplier.edit', $supplier) }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold border-bottom">
                    <i class="bi bi-info-circle me-2 text-primary"></i> Data Perusahaan
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 40%;">Status</td>
                            <td>
                                <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">PIC</td>
                            <td class="fw-semibold">{{ $supplier->pic_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Telepon</td>
                            <td>{{ $supplier->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $supplier->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">NPWP</td>
                            <td>{{ $supplier->npwp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat</td>
                            <td>{{ $supplier->address ?? '-' }}, {{ $supplier->city }}, {{ $supplier->province }} {{ $supplier->postal_code }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Syarat Bayar</td>
                            <td><span class="badge bg-light text-dark border">{{ $supplier->payment_terms ?? 'NET30' }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold border-bottom d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-receipt me-2 text-info"></i> Riwayat Purchase Order (PO)</span>
                    <a href="{{ route('admin.pembelian.create') }}" class="btn btn-sm btn-primary">Buat PO</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor PO</th>
                                    <th>Gudang</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplier->purchaseOrders as $po)
                                <tr>
                                    <td class="font-monospace fw-semibold">{{ $po->po_number }}</td>
                                    <td>{{ $po->warehouse->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $po->status_color }}">{{ $po->status_label }}</span>
                                    </td>
                                    <td class="text-end fw-bold">{{ rupiah($po->total_amount) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.pembelian.show', $po->id) }}" class="btn btn-sm btn-outline-primary">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada Purchase Order untuk supplier ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
