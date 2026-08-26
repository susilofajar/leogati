@extends('layouts.admin')

@section('title', 'Tambah Supplier Baru')
@section('header_title', 'Tambah Supplier Baru')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.supplier.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-[#0B5CFF] hover:border-[#0B5CFF] transition shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div>
                <h2 class="text-xl font-extrabold text-slate-900">
                    Tambah Supplier Baru
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Tambahkan data vendor atau distributor resmi untuk kebutuhan pembelian LEOGATISTORE.
                </p>
            </div>
        </div>
    </div>

    {{-- VALIDATION ERROR --}}
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14A2 2 0 003.82 21h16.36a2 2 0 001.71-3.14l-8.18-14a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-xs font-extrabold text-rose-800">
                        Data belum dapat disimpan
                    </h3>

                    <ul class="mt-1 text-xs text-rose-700 space-y-0.5 list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('admin.supplier.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- DATA UTAMA --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

                    <div class="px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M9 7h1m-1 4h1m4-4h1m-1 4h1"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900">
                                    Informasi Supplier
                                </h3>
                                <p class="text-[11px] text-slate-500">
                                    Informasi dasar perusahaan dan kontak supplier.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 space-y-5">

                        {{-- NAMA --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Perusahaan / Supplier
                                <span class="text-rose-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="PT. Distributor Teknologi Nusantara"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                       focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition
                                       @error('name') border-rose-400 bg-rose-50 @enderror">

                            @error('name')
                                <p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PIC & PHONE --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nama PIC / Kontak Person
                                </label>

                                <input
                                    type="text"
                                    name="pic_name"
                                    value="{{ old('pic_name') }}"
                                    placeholder="Budi Santoso"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                           focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nomor Telepon / WhatsApp
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="08123456789"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                           focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">
                            </div>

                        </div>

                        {{-- EMAIL & NPWP --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="sales@distributor.com"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                           focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    NPWP
                                </label>

                                <input
                                    type="text"
                                    name="npwp"
                                    value="{{ old('npwp') }}"
                                    placeholder="01.234.567.8-901.000"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                           focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">
                            </div>

                        </div>

                        {{-- ADDRESS --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alamat Lengkap
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                placeholder="Jl. Pergudangan Komputer No. 88"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                       focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">{{ old('address') }}</textarea>
                        </div>

                        {{-- CITY PROVINCE POSTAL --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Kota
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    value="{{ old('city') }}"
                                    placeholder="Jakarta Pusat"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                           focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Provinsi
                                </label>

                                <input
                                    type="text"
                                    name="province"
                                    value="{{ old('province') }}"
                                    placeholder="DKI Jakarta"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                           focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Kode Pos
                                </label>

                                <input
                                    type="text"
                                    name="postal_code"
                                    value="{{ old('postal_code') }}"
                                    placeholder="10110"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                           focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="space-y-6">

                {{-- PAYMENT --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

                    <div class="px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8c-1.5 0-3 .8-3 2s1.5 2 3 2 3 .8 3 2-1.5 2-3 2m0-10V6m0 12v-2m8-4a8 8 0 11-16 0 8 8 0 0116 0z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900">
                                    Pembayaran
                                </h3>
                                <p class="text-[11px] text-slate-500">
                                    Aturan pembayaran supplier.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">

                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Syarat Pembayaran
                        </label>

                        <input
                            type="text"
                            name="payment_terms"
                            value="{{ old('payment_terms', 'NET30') }}"
                            placeholder="NET30, COD, CBD"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs
                                   focus:outline-none focus:border-[#0B5CFF] focus:bg-white focus:ring-2 focus:ring-blue-50 transition">

                        <p class="mt-2 text-[11px] text-slate-500">
                            Contoh: NET30, COD, CBD.
                        </p>

                    </div>
                </div>

                {{-- STATUS --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

                    <div class="px-5 py-4 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-900">
                            Status Supplier
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            Tentukan apakah supplier dapat digunakan untuk PO.
                        </p>
                    </div>

                    <div class="p-5">

                        <label class="flex items-center justify-between cursor-pointer group">

                            <div>
                                <div class="text-xs font-bold text-slate-800">
                                    Supplier Aktif
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    Supplier dapat dipilih saat membuat PO.
                                </div>
                            </div>

                            <div class="relative">
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    id="is_active"
                                    class="peer sr-only"
                                    {{ old('is_active', true) ? 'checked' : '' }}>

                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-[#0B5CFF] transition"></div>

                                <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-sm
                                            peer-checked:translate-x-5 transition"></div>
                            </div>

                        </label>

                    </div>
                </div>

                {{-- ACTION --}}
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5">

                    <div class="flex flex-col gap-2">

                        <button
                            type="submit"
                            class="w-full px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl
                                   transition shadow-xs flex items-center justify-center gap-2">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>

                            Simpan Supplier
                        </button>

                        <a
                            href="{{ route('admin.supplier.index') }}"
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl
                                   transition text-center">

                            Batal
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>
@endsection