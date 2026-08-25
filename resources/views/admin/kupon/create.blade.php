@extends('layouts.admin')

@section('header_title', 'Buat Kupon Promo Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- TOP NAV -->
    <a href="{{ route('admin.kupon.index') }}" class="text-xs text-slate-500 hover:text-blue-600 font-bold flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Kupon
    </a>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-6">
        <h2 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Formulir Pembuatan Kupon Promo</h2>

        <form action="{{ route('admin.kupon.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="code" class="block text-xs font-bold text-slate-700 mb-1">
                        Kode Kupon (Huruf & Angka) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                        placeholder="Contoh: LEOHEMAT10"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-mono uppercase focus:outline-hidden focus:border-[#0B5CFF] @error('code') border-rose-500 @enderror">
                    @error('code')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                        Nama Promosi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Promo Flash Sale Akhir Pekan"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF] @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-700 mb-1">
                        Tipe Potongan Diskon <span class="text-rose-500">*</span>
                    </label>
                    <select name="type" id="type" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Persentase Diskon (%)</option>
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Potongan Nominal Tetap (Rp)</option>
                    </select>
                </div>

                <div>
                    <label for="value" class="block text-xs font-bold text-slate-700 mb-1">
                        Nilai Potongan <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="value" id="value" value="{{ old('value') }}" step="0.01" required
                        placeholder="Contoh: 10 (untuk 10%) atau 50000 (untuk Rp 50.000)"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF] @error('value') border-rose-500 @enderror">
                    @error('value')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="min_purchase_amount" class="block text-xs font-bold text-slate-700 mb-1">
                        Minimum Belanja (Rp)
                    </label>
                    <input type="number" name="min_purchase_amount" id="min_purchase_amount" value="{{ old('min_purchase_amount', 0) }}" step="1"
                        placeholder="Contoh: 500000"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <div>
                    <label for="max_discount_amount" class="block text-xs font-bold text-slate-700 mb-1">
                        Batas Maksimal Diskon (Rp) &mdash; Khusus Persen
                    </label>
                    <input type="number" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}" step="1"
                        placeholder="Contoh: 100000 (kosongkan jika tanpa batas)"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="usage_limit" class="block text-xs font-bold text-slate-700 mb-1">
                        Batas Total Kuota Pemakaian
                    </label>
                    <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}" step="1"
                        placeholder="Contoh: 100 (kosongkan jika tak terbatas)"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <div>
                    <label for="start_date" class="block text-xs font-bold text-slate-700 mb-1">
                        Tanggal Mulai Berlaku
                    </label>
                    <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-bold text-slate-700 mb-1">
                        Tanggal Berakhir
                    </label>
                    <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 rounded border-slate-300">
                    <span>Aktifkan kupon ini sekarang</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.kupon.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-lg shadow-xs transition">
                    Simpan Kupon Promo
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
