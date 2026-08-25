@extends('layouts.admin')

@section('title', 'Edit Supplier — ' . $supplier->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.supplier.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold">Edit Data Supplier</h1>
            <p class="text-muted mb-0">Kode: <span class="font-monospace fw-bold text-primary">{{ $supplier->code }}</span></p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.supplier.update', $supplier) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Perusahaan / Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $supplier->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama PIC (Kontak Person)</label>
                                <input type="text" name="pic_name" value="{{ old('pic_name', $supplier->pic_name) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NPWP</label>
                                <input type="text" name="npwp" value="{{ old('npwp', $supplier->npwp) }}" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="form-control">{{ old('address', $supplier->address) }}</textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kota</label>
                                <input type="text" name="city" value="{{ old('city', $supplier->city) }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Provinsi</label>
                                <input type="text" name="province" value="{{ old('province', $supplier->province) }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kode Pos</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $supplier->postal_code) }}" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Syarat Pembayaran</label>
                                <input type="text" name="payment_terms" value="{{ old('payment_terms', $supplier->payment_terms) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status Keaktifan</label>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $supplier->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Perbarui Supplier
                            </button>
                            <a href="{{ route('admin.supplier.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
