@extends('layouts.admin')

@section('title', 'Penyesuaian Stok — ' . $varian->sku)

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.inventaris.mutasi', $varian->id) }}"
            class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div>
            <h2 class="text-xl font-extrabold text-slate-900">
                Penyesuaian Stok Manual
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                {{ $varian->product->name ?? '' }} — {{ $varian->name }}
            </p>
        </div>
    </div>

    {{-- ERROR --}}
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                </svg>

                <div>
                    <h3 class="text-sm font-bold text-rose-700 mb-2">
                        Terdapat kesalahan pada form:
                    </h3>

                    <ul class="text-xs text-rose-600 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>• {{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-3xl">

        {{-- FORM CARD --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 21v-7m0 0V3m0 11h16M20 3v7m0 0v11m0-11H4"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-900">
                        Form Penyesuaian Stok
                    </h3>
                    <p class="text-xs text-slate-500">
                        Lakukan koreksi stok berdasarkan hasil pengecekan fisik.
                    </p>
                </div>
            </div>

            <div class="p-5">

                {{-- INFO STOCK --}}
                <div class="mb-5 p-4 rounded-2xl border border-blue-100 bg-blue-50">
                    <div class="text-xs text-slate-500 mb-1">
                        Stok Saat Ini
                    </div>

                    <div class="text-lg font-extrabold text-[#0B5CFF]">
                        {{ number_format($varian->stock) }} Unit
                    </div>

                    <p class="text-xs text-slate-600 mt-2">
                        Gunakan angka positif untuk menambah stok dan angka negatif untuk mengurangi stok.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.inventaris.adjust', $varian->id) }}">
                    @csrf

                    {{-- GUDANG --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Gudang <span class="text-rose-500">*</span>
                        </label>

                        <select name="warehouse_id"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('warehouse_id') border-rose-300 @enderror"
                            required>

                            <option value="">-- Pilih Gudang --</option>

                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}"
                                    {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }}
                                    ({{ $wh->code }})
                                    {{ $wh->is_default ? ' — Utama' : '' }}
                                </option>
                            @endforeach

                        </select>

                        @error('warehouse_id')
                            <p class="text-[11px] text-rose-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- QTY --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Perubahan Stok <span class="text-rose-500">*</span>
                        </label>

                        <input type="number"
                            name="quantity_change"
                            value="{{ old('quantity_change') }}"
                            placeholder="Contoh: +10 atau -3"
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('quantity_change') border-rose-300 @enderror">

                        <p class="text-[11px] text-slate-500 mt-1">
                            Positif = tambah stok • Negatif = kurangi stok
                        </p>

                        @error('quantity_change')
                            <p class="text-[11px] text-rose-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- NOTES --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Alasan Penyesuaian <span class="text-rose-500">*</span>
                        </label>

                        <textarea
                            name="notes"
                            rows="4"
                            required
                            placeholder="Contoh: Stok fisik tidak sesuai hasil stock opname..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs resize-none focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('notes') border-rose-300 @enderror">{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="text-[11px] text-rose-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs">
                            Simpan Penyesuaian
                        </button>

                        <a href="{{ route('admin.inventaris.mutasi', $varian->id) }}"
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition text-center">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>
@endsection