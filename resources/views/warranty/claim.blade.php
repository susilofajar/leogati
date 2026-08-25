@extends('layouts.app')

@section('title', 'Formulir Pengajuan Klaim Garansi Resmi')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

    <!-- BREADCRUMB & HEADER -->
    <div class="space-y-2">
        <nav class="flex text-xs text-slate-500 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('warranty.check') }}" class="hover:text-blue-600">Cek Garansi</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Formulir Klaim</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
            Pengajuan Klaim Garansi Resmi
        </h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Layanan klaim garansi purna jual resmi LEOGATISTORE dengan proses perbaikan cepat dan transparan.
        </p>
    </div>

    <!-- PREFILLED PRODUCT INFO IF AVAILABLE -->
    @if($serial)
        <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold text-blue-600 tracking-wider">Unit Terverifikasi</span>
                <h3 class="text-sm font-bold text-slate-900">
                    {{ $serial->productVariant->product->name ?? 'Produk' }} — {{ $serial->productVariant->name ?? '' }}
                </h3>
                <p class="text-xs text-slate-600 font-mono">S/N: <strong class="text-slate-900">{{ $serial->serial_number }}</strong></p>
            </div>
            <div class="text-left sm:text-right text-xs">
                <span class="text-slate-500 text-[10px] uppercase block font-semibold">Garansi Berlaku Hingga</span>
                <span class="font-bold text-emerald-700">{{ $serial->warranty_expires_at ? tgl_indo($serial->warranty_expires_at) : 'Seumur Hidup' }}</span>
            </div>
        </div>
    @endif

    <!-- CLAIM FORM -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md">
        <form action="{{ route('warranty.submit_claim') }}" method="POST" class="space-y-6">
            @csrf

            <!-- SERIAL NUMBER INPUT -->
            <div>
                <label for="serial_number" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nomor Seri Unit (Serial Number) <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="serial_number" id="serial_number" 
                    value="{{ old('serial_number', $prefilledSn ?? ($serial->serial_number ?? '')) }}" required
                    placeholder="Contoh: SN-ROG-2026-98124"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono uppercase focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition @error('serial_number') border-rose-500 bg-rose-50 @enderror">
                @error('serial_number')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
                <p class="mt-1.5 text-[11px] text-slate-400">
                    * Pastikan nomor seri unit cocok dengan yang Anda beli menggunakan akun ini.
                </p>
            </div>

            <!-- ISSUE CATEGORY -->
            <div>
                <label for="issue_category" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Kategori Kerusakan / Kendala <span class="text-rose-500">*</span>
                </label>
                <select name="issue_category" id="issue_category" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition @error('issue_category') border-rose-500 bg-rose-50 @enderror">
                    <option value="">-- Pilih Kategori Kendala --</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('issue_category') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('issue_category')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ISSUE DESCRIPTION -->
            <div>
                <label for="issue_description" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Kronologi & Deskripsi Lengkap Kerusakan <span class="text-rose-500">*</span>
                </label>
                <textarea name="issue_description" id="issue_description" rows="5" required
                    placeholder="Jelaskan secara rinci gejala kerusakan yang terjadi, kapan mulai terjadi, dan langkah-langkah yang sudah dicoba (minimal 20 karakter)..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition leading-relaxed @error('issue_description') border-rose-500 bg-rose-50 @enderror">{{ old('issue_description') }}</textarea>
                @error('issue_description')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-[11px] text-slate-400">
                    Informasi detail membantu tim teknisi mempercepat diagnosis dan proses perbaikan unit Anda.
                </p>
            </div>

            <!-- TERMS CHECKBOX -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                <p class="font-bold text-slate-800">Ketentuan Layanan Garansi LEOGATISTORE:</p>
                <ul class="list-disc list-inside space-y-1 text-[11px] text-slate-500 leading-relaxed">
                    <li>Garansi mencakup kerusakan manufaktur dan fungsional suku cadang asli.</li>
                    <li>Garansi tidak berlaku apabila segel pabrik rusak, terkena cairan, atau kerusakan akibat kelalaian pemakaian (force majeure).</li>
                    <li>Biaya pengiriman unit ke service center ditanggung oleh pelanggan, pengiriman kembali unit yang selesai diperbaiki ditanggung oleh LEOGATISTORE.</li>
                </ul>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="flex items-center justify-end space-x-3 pt-2">
                <a href="{{ route('warranty.check') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-md transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Kirim Pengajuan Klaim Garansi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
