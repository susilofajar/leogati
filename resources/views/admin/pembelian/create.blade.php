@extends('layouts.admin')

@section('title', 'Buat Purchase Order Baru')

@section('content')
<div class="container-fluid py-4" x-data="poBuilder()">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.pembelian.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 fw-bold">Buat Purchase Order (PO) Baru</h1>
    </div>

    @if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.pembelian.store') }}">
        @csrf

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent fw-semibold border-bottom">
                        <i class="bi bi-building me-2 text-primary"></i> Data Supplier & Gudang
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->name }} ({{ $sup->code }})
                                </option>
                                @endforeach
                            </select>
                            @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gudang Tujuan Penerimaan <span class="text-danger">*</span></label>
                            <select name="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Gudang Tujuan --</option>
                                @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('warehouse_id', $wh->is_default ? $wh->id : '') == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }} ({{ $wh->code }}){{ $wh->is_default ? ' — Utama' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Estimasi Tanggal Tiba</label>
                            <input type="date" name="expected_at" value="{{ old('expected_at') }}" class="form-control">
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Catatan Internal</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Catatan pengiriman, no invoice supplier, dll">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-light">
                    <div class="card-header bg-transparent fw-semibold border-bottom">
                        <i class="bi bi-calculator me-2 text-success"></i> Ringkasan Biaya PO
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                        <div class="text-muted mb-2">Total Estimasi Pembelian</div>
                        <div class="display-6 fw-bold text-primary mb-3">
                            Rp <span x-text="formatRupiah(grandTotal)">0</span>
                        </div>
                        <div class="text-muted small">
                            <span x-text="items.length"></span> jenis barang dipesan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Item Barang PO --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold border-bottom d-flex justify-content-between align-items-center">
                <span><i class="bi bi-boxes me-2 text-primary"></i> Daftar Barang yang Dipesan</span>
                <button type="button" class="btn btn-sm btn-outline-primary" @click="addItem()">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Baris Produk
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 45%;">Produk / Varian</th>
                                <th style="width: 15%;" class="text-center">Jumlah Pesan</th>
                                <th style="width: 20%;" class="text-end">Harga Beli Satuan (Rp)</th>
                                <th style="width: 15%;" class="text-end">Subtotal (Rp)</th>
                                <th style="width: 5%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td>
                                        <select :name="'items[' + index + '][product_variant_id]'" x-model="item.product_variant_id" @change="updatePrice(item)" class="form-select form-select-sm" required>
                                            <option value="">-- Pilih Produk / Varian --</option>
                                            @foreach($variants as $v)
                                            <option value="{{ $v->id }}" data-cost="{{ $v->cost_price ?: $v->price * 0.8 }}">
                                                {{ $v->product->name ?? '' }} — {{ $v->name }} ({{ $v->sku }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" :name="'items[' + index + '][quantity_ordered]'" x-model.number="item.quantity_ordered" min="1" class="form-control form-control-sm text-center" required>
                                    </td>
                                    <td>
                                        <input type="number" :name="'items[' + index + '][unit_cost]'" x-model.number="item.unit_cost" min="0" step="1000" class="form-control form-control-sm text-end" required>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        <span x-text="formatRupiah(item.quantity_ordered * item.unit_cost)"></span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0" @click="removeItem(index)" x-show="items.length > 1">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle me-1"></i> Simpan Draft Purchase Order
            </button>
            <a href="{{ route('admin.pembelian.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function poBuilder() {
    return {
        items: [
            { product_variant_id: '', quantity_ordered: 1, unit_cost: 0 }
        ],
        addItem() {
            this.items.push({ product_variant_id: '', quantity_ordered: 1, unit_cost: 0 });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },
        updatePrice(item) {
            const selectEl = event.target;
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            const cost = selectedOpt.getAttribute('data-cost');
            if (cost) {
                item.unit_cost = parseFloat(cost);
            }
        },
        get grandTotal() {
            return this.items.reduce((sum, item) => sum + ((item.quantity_ordered || 0) * (item.unit_cost || 0)), 0);
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    };
}
</script>
@endsection
