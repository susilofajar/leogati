@extends('layouts.admin')

@section('header_title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Tambah Produk Baru ke Katalog</h2>
            <p class="text-xs text-slate-500 mt-0.5">Lengkapi informasi dasar perangkat teknologi, penetapan harga jual, dan stok awal</p>
        </div>

        <a href="{{ route('admin.produk.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('admin.produk.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- SECTION 1: INFORMASI DASAR -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF] border-b border-slate-100 pb-2">
                1. Informasi Dasar Produk
            </h3>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                    Nama Lengkap Produk <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    placeholder="Contoh: ASUS ROG Strix SCAR 16 Gaming Laptop"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                @error('name')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border @error('category_id') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        <option value="">Pilih Kategori Produk...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_id" class="block text-xs font-bold text-slate-700 mb-1">
                        Merek Resmi <span class="text-rose-500">*</span>
                    </label>
                    <select name="brand_id" id="brand_id" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border @error('brand_id') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        <option value="">Pilih Merek Produsen...</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="warranty_period_months" class="block text-xs font-bold text-slate-700 mb-1">
                        Masa Garansi Resmi (Bulan) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="warranty_period_months" id="warranty_period_months" value="{{ old('warranty_period_months', 24) }}" required min="0" max="120"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 mb-1">
                        Status Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif (Tampil di Toko)</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Disembunyikan)</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
                    </select>
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-300 text-[#0B5CFF] focus:ring-[#0B5CFF]">
                        <span>Tandai Produk Unggulan</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- SECTION 2: VARIAN UTAMA & HARGA -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF] border-b border-slate-100 pb-2">
                2. Varian Utama, Harga & Stok Fisik
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="sku" class="block text-xs font-bold text-slate-700 mb-1">
                        Kode SKU Unik <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
                        placeholder="Contoh: ASUS-G634JZ-001"
                        class="w-full px-3.5 py-2 bg-slate-50 border @error('sku') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs font-mono uppercase focus:outline-hidden focus:border-[#0B5CFF]">
                    @error('sku')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weight_grams" class="block text-xs font-bold text-slate-700 mb-1">
                        Berat Produk (Gram) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="weight_grams" id="weight_grams" value="{{ old('weight_grams', 1000) }}" required min="1"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold text-slate-700 mb-1">
                        Harga Jual (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" step="1000"
                        placeholder="Contoh: 15000000"
                        class="w-full px-3.5 py-2 bg-slate-50 border @error('price') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs font-bold focus:outline-hidden focus:border-[#0B5CFF]">
                    @error('price')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cost_price" class="block text-xs font-bold text-slate-700 mb-1">
                        Harga Modal / HPP (Rp)
                    </label>
                    <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price', 0) }}" min="0" step="1000"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold text-slate-700 mb-1">
                        Stok Fisik Awal <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 10) }}" required min="0"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold focus:outline-hidden focus:border-[#0B5CFF]">
                </div>
            </div>
        </div>

        <!-- SECTION 3: DESKRIPSI -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF] border-b border-slate-100 pb-2">
                3. Deskripsi & Rincian Produk
            </h3>

            <div>
                <label for="short_description" class="block text-xs font-bold text-slate-700 mb-1">
                    Ringkasan Singkat (Muncul di Kartu Katalog)
                </label>
                <textarea name="short_description" id="short_description" rows="2"
                    placeholder="Ringkasan spesifikasi unggulan produk (maksimal 500 karakter)..."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">{{ old('short_description') }}</textarea>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1">
                    Deskripsi Lengkap & Keunggulan Produk
                </label>
                <textarea name="description" id="description" rows="5"
                    placeholder="Rincian fitur, paket dalam kemasan, dan informasi tambahan..."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- SUBMIT BUTTONS -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('admin.produk.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-extrabold rounded-xl shadow-md transition">
                Simpan Produk Baru
            </button>
        </div>
    </form>

</div>
@endsection
