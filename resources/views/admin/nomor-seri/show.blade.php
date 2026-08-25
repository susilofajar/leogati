@extends('layouts.admin')

@section('title', 'Detail Nomor Seri — ' . $nomor_seri->serial_number)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.nomor_seri.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h1 class="h4 mb-0 fw-bold">Nomor Seri: <span class="text-primary font-monospace">{{ $nomor_seri->serial_number }}</span></h1>
            <p class="text-muted mb-0">{{ $nomor_seri->productVariant->product->name ?? '' }} — {{ $nomor_seri->productVariant->name ?? '' }}</p>
        </div>
        <span class="badge bg-{{ $nomor_seri->status_color }} fs-6 px-3 py-2">
            Status: {{ $nomor_seri->status_label }}
        </span>
    </div>

    <div class="row g-4">
        {{-- Info Unit & Produk --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent fw-semibold border-bottom">
                    <i class="bi bi-laptop me-2 text-primary"></i> Informasi Produk & Unit
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 40%;">Nama Produk</td>
                            <td class="fw-semibold">{{ $nomor_seri->productVariant->product->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Varian Produk</td>
                            <td>{{ $nomor_seri->productVariant->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">SKU</td>
                            <td class="font-monospace">{{ $nomor_seri->productVariant->sku ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lokasi Gudang</td>
                            <td>{{ $nomor_seri->warehouse->name ?? 'Gudang Pusat' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Garansi Resmi</td>
                            <td>
                                @if($nomor_seri->warranty_expires_at)
                                    <span class="{{ $nomor_seri->isUnderWarranty() ? 'badge bg-success' : 'badge bg-danger' }}">
                                        {{ $nomor_seri->isUnderWarranty() ? 'Aktif s/d ' : 'Kadaluarsa ' }} {{ tgl_indo($nomor_seri->warranty_expires_at) }}
                                    </span>
                                @else
                                    <span class="text-muted">Tidak tercatat</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Info Pembelian & Penjualan --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent fw-semibold border-bottom">
                    <i class="bi bi-clock-history me-2 text-info"></i> Riwayat Siklus Hidup Unit
                </div>
                <div class="card-body">
                    <div class="timeline">
                        {{-- Penerimaan Barang --}}
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="fw-bold text-dark">
                                <i class="bi bi-box-arrow-in-down text-success me-1"></i> Penerimaan dari Supplier
                            </div>
                            <div class="text-muted small">
                                Tanggal: {{ $nomor_seri->purchased_at ? tgl_indo($nomor_seri->purchased_at) : '-' }}
                            </div>
                            @if($nomor_seri->purchaseOrder)
                            <div class="small">
                                PO: <a href="{{ route('admin.pembelian.show', $nomor_seri->purchaseOrder->id) }}">#{{ $nomor_seri->purchaseOrder->po_number }}</a>
                                (Supplier: {{ $nomor_seri->purchaseOrder->supplier->name ?? '-' }})
                            </div>
                            @endif
                        </div>

                        {{-- Penjualan ke Pelanggan --}}
                        <div>
                            <div class="fw-bold text-dark">
                                <i class="bi bi-cart-check text-primary me-1"></i> Penjualan ke Pelanggan
                            </div>
                            <div class="text-muted small">
                                Tanggal Terjual: {{ $nomor_seri->sold_at ? tgl_indo($nomor_seri->sold_at) : 'Belum terjual' }}
                            </div>
                            @if($nomor_seri->orderItem && $nomor_seri->orderItem->order)
                            <div class="small">
                                Pesanan: <a href="{{ route('admin.pesanan.show', $nomor_seri->orderItem->order->id) }}">#{{ $nomor_seri->orderItem->order->order_number }}</a>
                            </div>
                            @endif
                            @if($nomor_seri->customer)
                            <div class="small">
                                Pembeli: {{ $nomor_seri->customer->name }} ({{ $nomor_seri->customer->email }})
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
