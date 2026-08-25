@extends('layouts.admin')

@section('title', 'Penyesuaian Stok — ' . $varian->sku)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.inventaris.mutasi', $varian->id) }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h4 mb-0 fw-bold">Penyesuaian Stok Manual</h1>
            <p class="text-muted mb-0">{{ $varian->product->name ?? '' }} — {{ $varian->name }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold border-bottom">
                    <i class="bi bi-sliders me-2 text-warning"></i> Form Penyesuaian
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <strong>Stok saat ini: {{ number_format($varian->stock) }} unit</strong><br>
                        Gunakan nilai positif untuk menambah stok, negatif untuk mengurangi.
                    </div>

                    <form method="POST" action="{{ route('admin.inventaris.adjust', $varian->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gudang <span class="text-danger">*</span></label>
                            <select name="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }} ({{ $wh->code }}){{ $wh->is_default ? ' — Utama' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Perubahan Stok <span class="text-danger">*</span></label>
                            <input type="number" name="quantity_change" value="{{ old('quantity_change') }}"
                                   class="form-control @error('quantity_change') is-invalid @enderror"
                                   placeholder="Contoh: +10 atau -3" required>
                            <div class="form-text">Positif = tambah stok. Negatif = kurangi stok.</div>
                            @error('quantity_change')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Alasan Penyesuaian <span class="text-danger">*</span></label>
                            <textarea name="notes" rows="3"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Contoh: Stok fisik tidak sesuai hasil stock opname..." required>{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle me-1"></i> Simpan Penyesuaian
                            </button>
                            <a href="{{ route('admin.inventaris.mutasi', $varian->id) }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
