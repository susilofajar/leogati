@extends('layouts.admin')

@section('title', 'Detail Purchase Order — #' . $pembelian->po_number)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.pembelian.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h1 class="h3 mb-0 fw-bold">Purchase Order <span class="text-primary font-monospace">#{{ $pembelian->po_number }}</span></h1>
            <p class="text-muted mb-0">Dibuat oleh: {{ $pembelian->creator->name ?? 'Admin' }} pada {{ tgl_indo($pembelian->created_at) }}</p>
        </div>
        <span class="badge bg-{{ $pembelian->status_color }} fs-6 px-3 py-2">
            Status: {{ $pembelian->status_label }}
        </span>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Info Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent fw-semibold border-bottom">
                    <i class="bi bi-building me-2 text-primary"></i> Data Supplier
                </div>
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-1">{{ $pembelian->supplier->name ?? '-' }}</h5>
                    <div class="text-muted small mb-2">Kode: {{ $pembelian->supplier->code ?? '-' }}</div>
                    <div class="small mb-1"><i class="bi bi-person me-1"></i> PIC: {{ $pembelian->supplier->pic_name ?? '-' }}</div>
                    <div class="small mb-1"><i class="bi bi-telephone me-1"></i> Telp: {{ $pembelian->supplier->phone ?? '-' }}</div>
                    <div class="small"><i class="bi bi-envelope me-1"></i> Email: {{ $pembelian->supplier->email ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent fw-semibold border-bottom">
                    <i class="bi bi-geo-alt me-2 text-info"></i> Gudang Tujuan
                </div>
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-1">{{ $pembelian->warehouse->name ?? '-' }}</h5>
                    <div class="text-muted small mb-2">Kode: {{ $pembelian->warehouse->code ?? '-' }}</div>
                    <div class="small mb-1">{{ $pembelian->warehouse->address ?? '-' }}</div>
                    <div class="small text-muted">{{ $pembelian->warehouse->city ?? '' }}, {{ $pembelian->warehouse->province ?? '' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-header bg-transparent fw-semibold border-bottom">
                    <i class="bi bi-cash-stack me-2 text-success"></i> Nilai Transaksi & Aksi
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="text-muted small">Total Biaya PO</div>
                        <div class="h3 fw-bold text-primary mb-3">{{ rupiah($pembelian->total_amount) }}</div>
                    </div>

                    <div class="d-flex gap-2">
                        @if($pembelian->status === 'draft')
                        <form method="POST" action="{{ route('admin.pembelian.kirim', $pembelian->id) }}" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 btn-sm">
                                <i class="bi bi-send me-1"></i> Kirim ke Supplier
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.pembelian.batalkan', $pembelian->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin batalkan PO ini?')">
                                Batalkan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Item Barang --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent fw-semibold border-bottom">
            <i class="bi bi-boxes me-2 text-primary"></i> Rincian Barang Dipesan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk / Varian</th>
                            <th>SKU</th>
                            <th class="text-center">Dipesan</th>
                            <th class="text-center">Sudah Diterima</th>
                            <th class="text-center">Sisa</th>
                            <th class="text-end">Harga Beli</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembelian->items as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->productVariant->product->name ?? '-' }}</div>
                                <div class="text-muted small">
                                    {{ $item->productVariant->name }}
                                    @if($item->productVariant->is_serialized)
                                        <span class="badge bg-info text-dark ms-1">Serial Tracked</span>
                                    @endif
                                </div>
                            </td>
                            <td class="font-monospace small">{{ $item->productVariant->sku }}</td>
                            <td class="text-center fw-bold">{{ number_format($item->quantity_ordered) }}</td>
                            <td class="text-center text-success fw-bold">{{ number_format($item->quantity_received) }}</td>
                            <td class="text-center {{ $item->remaining_quantity > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                {{ number_format($item->remaining_quantity) }}
                            </td>
                            <td class="text-end">{{ rupiah($item->unit_cost) }}</td>
                            <td class="text-end fw-bold">{{ rupiah($item->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Form Penerimaan Barang (Goods Receipt) jika status Sent atau Partial --}}
    @if(in_array($pembelian->status, ['sent', 'partial']))
    <div class="card border-0 shadow-sm border-top border-primary border-3">
        <div class="card-header bg-transparent fw-bold text-dark py-3">
            <i class="bi bi-box-seam me-2 text-primary"></i> Form Penerimaan Barang Masuk (Goods Receipt)
        </div>
        <div class="card-body p-4">
            <p class="text-muted small mb-4">
                Masukkan jumlah barang fisik yang diterima di gudang. Untuk produk yang ditandai <strong>Serial Tracked</strong>, masukkan nomor seri satu per baris pada kolom yang disediakan.
            </p>

            <form method="POST" action="{{ route('admin.pembelian.terima', $pembelian->id) }}">
                @csrf

                @foreach($pembelian->items as $idx => $item)
                    @if($item->remaining_quantity > 0)
                    <input type="hidden" name="items[{{ $idx }}][po_item_id]" value="{{ $item->id }}">

                    <div class="p-3 border rounded mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="fw-bold">{{ $item->productVariant->product->name ?? '-' }}</span> — {{ $item->productVariant->name }} ({{ $item->productVariant->sku }})
                                @if($item->productVariant->is_serialized)
                                    <span class="badge bg-info text-dark ms-1">Wajib Serial</span>
                                @endif
                            </div>
                            <span class="badge bg-secondary">Sisa Belum Diterima: {{ $item->remaining_quantity }} unit</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Jumlah Diterima Hari Ini</label>
                                <input type="number" name="items[{{ $idx }}][quantity_received]" value="{{ old('items.'.$idx.'.quantity_received', 0) }}" min="0" max="{{ $item->remaining_quantity }}" class="form-control form-control-sm text-center">
                            </div>

                            @if($item->productVariant->is_serialized)
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nomor Seri Unit (1 Nomor Seri per Baris)</label>
                                <textarea name="items[{{ $idx }}][serial_numbers]" rows="3" class="form-control form-control-sm font-monospace" placeholder="SN-ASUS-001&#10;SN-ASUS-002">{{ old('items.'.$idx.'.serial_numbers') }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Masa Garansi (Bulan)</label>
                                <input type="number" name="items[{{ $idx }}][warranty_months]" value="{{ old('items.'.$idx.'.warranty_months', 24) }}" min="0" class="form-control form-control-sm text-center">
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach

                <button type="submit" class="btn btn-success mt-2">
                    <i class="bi bi-box-arrow-in-down me-1"></i> Proses Penerimaan & Tambah Stok
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
