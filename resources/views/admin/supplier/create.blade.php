@extends('layouts.admin')

@section('title', 'Tambah Supplier Baru')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.supplier.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 fw-bold">Tambah Supplier Baru</h1>
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
                    <form method="POST" action="{{ route('admin.supplier.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Perusahaan / Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required placeholder="PT. Distributor Teknologi Nusantara">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama PIC (Kontak Person)</label>
                                <input type="text" name="pic_name" value="{{ old('pic_name') }}" class="form-control" placeholder="Budi Santoso">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="08123456789">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="sales@distributor.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NPWP</label>
                                <input type="text" name="npwp" value="{{ old('npwp') }}" class="form-control" placeholder="01.234.567.8-901.000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="form-control" placeholder="Jl. Pergudangan Komputer No. 88">{{ old('address') }}</textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kota</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="form-control" placeholder="Jakarta Pusat">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Provinsi</label>
                                <input type="text" name="province" value="{{ old('province') }}" class="form-control" placeholder="DKI Jakarta">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kode Pos</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="form-control" placeholder="10110">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Syarat Pembayaran (Payment Terms)</label>
                                <input type="text" name="payment_terms" value="{{ old('payment_terms', 'NET30') }}" class="form-control" placeholder="Contoh: NET30, COD, CBD">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status Keaktifan</label>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">Aktif (Bisa buat PO)</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Simpan Supplier
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
