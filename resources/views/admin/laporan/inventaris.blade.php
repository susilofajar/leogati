@extends('layouts.admin')

@section('header_title', 'Laporan Inventaris')

@section('content')
<div class="space-y-6">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-4">

        <div>
            <div class="flex items-center gap-2 mb-2">

                <span class="inline-flex items-center justify-center
                             w-7 h-7 rounded-lg bg-blue-50 text-[#0B5CFF]">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>

                    </svg>

                </span>

                <span class="text-[10px] font-bold uppercase
                             tracking-[0.15em] text-slate-400">
                    Inventory Control
                </span>

            </div>

            <h2 class="text-2xl font-black tracking-tight text-slate-900">
                Laporan Inventaris & Stok
            </h2>

            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Pantau kondisi stok, produk tidak bergerak,
                dan seluruh aktivitas mutasi inventaris.
            </p>
        </div>

        <div class="flex items-center gap-2">

            <span class="inline-flex items-center gap-2 px-3 py-2
                         rounded-xl bg-emerald-50 border border-emerald-100
                         text-[11px] font-bold text-emerald-700">

                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                Inventory Aktif
            </span>

            <span class="px-3 py-2 rounded-xl bg-slate-100
                         text-[11px] font-bold text-slate-600">
                {{ now()->translatedFormat('d F Y') }}
            </span>

        </div>

    </div>


    {{-- =========================================================
         INVENTORY KPI
    ========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Critical Stock --}}
        <div class="relative overflow-hidden bg-white rounded-2xl
                    border border-slate-200 shadow-sm p-5">

            <div class="absolute -right-8 -top-8 w-28 h-28
                        rounded-full bg-rose-50"></div>

            <div class="relative">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-[10px] font-black uppercase
                                  tracking-[0.12em] text-slate-500">
                            Stok Kritis
                        </p>

                        <p class="text-3xl font-black tracking-tight
                                  text-slate-900 mt-2">
                            {{ $lowStock->count() }}
                        </p>

                        <p class="text-[10px] text-slate-500 mt-1">
                            Varian membutuhkan perhatian
                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-rose-50
                                text-rose-600 flex items-center justify-center">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856
                                     c1.54 0 2.502-1.667 1.732-3L13.732 4
                                     c-.77-1.333-2.694-1.333-3.464 0L3.34 16
                                     c-.77 1.333.192 3 1.732 3z"/>

                        </svg>

                    </div>

                </div>

                <div class="mt-4">

                    <span class="inline-flex items-center gap-1.5
                                 px-2.5 py-1 rounded-lg bg-rose-50
                                 text-[9px] font-black text-rose-600">

                        ≤ 5 UNIT

                    </span>

                </div>

            </div>
        </div>


        {{-- Dead Stock --}}
        <div class="relative overflow-hidden bg-white rounded-2xl
                    border border-slate-200 shadow-sm p-5">

            <div class="absolute -right-8 -top-8 w-28 h-28
                        rounded-full bg-amber-50"></div>

            <div class="relative">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-[10px] font-black uppercase
                                  tracking-[0.12em] text-slate-500">
                            Dead Stock
                        </p>

                        <p class="text-3xl font-black tracking-tight
                                  text-slate-900 mt-2">
                            {{ $deadStock->count() }}
                        </p>

                        <p class="text-[10px] text-slate-500 mt-1">
                            Tidak terjual selama 90 hari
                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-amber-50
                                text-amber-600 flex items-center justify-center">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0
                                     11-18 0 9 9 0 0118 0z"/>

                        </svg>

                    </div>

                </div>

                <div class="mt-4">

                    <span class="inline-flex items-center gap-1.5
                                 px-2.5 py-1 rounded-lg bg-amber-50
                                 text-[9px] font-black text-amber-600">

                        90 HARI

                    </span>

                </div>

            </div>
        </div>


        {{-- Stock Movement --}}
        <div class="relative overflow-hidden bg-white rounded-2xl
                    border border-slate-200 shadow-sm p-5">

            <div class="absolute -right-8 -top-8 w-28 h-28
                        rounded-full bg-blue-50"></div>

            <div class="relative">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-[10px] font-black uppercase
                                  tracking-[0.12em] text-slate-500">
                            Mutasi Stok
                        </p>

                        <p class="text-3xl font-black tracking-tight
                                  text-slate-900 mt-2">
                            {{ $movements->total() }}
                        </p>

                        <p class="text-[10px] text-slate-500 mt-1">
                            Aktivitas inventaris tercatat
                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-blue-50
                                text-[#0B5CFF] flex items-center justify-center">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4 7h16M4 12h16M4 17h10"/>

                        </svg>

                    </div>

                </div>

                <div class="mt-4">

                    <span class="inline-flex items-center gap-1.5
                                 px-2.5 py-1 rounded-lg bg-blue-50
                                 text-[9px] font-black text-[#0B5CFF]">

                        STOCK MOVEMENT

                    </span>

                </div>

            </div>
        </div>

    </div>


    {{-- =========================================================
         CRITICAL STOCK
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">

            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3">

                <div class="flex items-start gap-3">

                    <div class="w-10 h-10 rounded-xl bg-rose-50
                                text-rose-600 flex items-center justify-center
                                shrink-0">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856
                                     c1.54 0 2.502-1.667 1.732-3L13.732 4
                                     c-.77-1.333-2.694-1.333-3.464 0L3.34 16
                                     c-.77 1.333.192 3 1.732 3z"/>

                        </svg>

                    </div>

                    <div>

                        <div class="flex items-center gap-2">

                            <h3 class="text-sm font-black text-slate-900">
                                Stok Kritis
                            </h3>

                            <span class="px-2 py-0.5 rounded-md
                                         bg-rose-50 text-rose-600
                                         text-[9px] font-black">
                                ≤ 5 UNIT
                            </span>

                        </div>

                        <p class="text-[11px] text-slate-500 mt-1">
                            {{ $lowStock->count() }}
                            varian produk memerlukan pengisian segera.
                        </p>

                    </div>

                </div>

                @if($lowStock->count() > 0)

                    <span class="inline-flex items-center gap-1.5
                                 px-3 py-1.5 rounded-lg bg-rose-50
                                 text-[10px] font-black text-rose-600">

                        PERLU RESTOCK

                    </span>

                @endif

            </div>

        </div>


        @if($lowStock->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full text-xs">

                    <thead class="bg-slate-50 border-b border-slate-100">

                        <tr class="text-[9px] uppercase tracking-wider
                                   font-black text-slate-400">

                            <th class="py-3.5 px-5 text-left">Produk</th>
                            <th class="py-3.5 px-4 text-left">SKU</th>
                            <th class="py-3.5 px-4 text-left">Kategori</th>
                            <th class="py-3.5 px-4 text-right">Harga Jual</th>
                            <th class="py-3.5 px-5 text-right">Stok</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($lowStock as $variant)

                            <tr class="hover:bg-rose-50/30 transition">

                                <td class="py-3.5 px-5">

                                    <div class="flex items-center gap-3">

                                        <div class="w-9 h-9 rounded-lg bg-slate-100
                                                    flex items-center justify-center
                                                    text-[10px] font-black text-slate-500
                                                    shrink-0">

                                            {{ strtoupper(substr($variant->product?->name ?? 'P', 0, 1)) }}

                                        </div>

                                        <div class="min-w-0">

                                            <p class="font-bold text-slate-900
                                                      truncate max-w-[220px]">

                                                {{ $variant->product?->name }}

                                            </p>

                                            <p class="text-[10px] text-slate-400 mt-0.5">
                                                {{ $variant->name }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="py-3.5 px-4">

                                    <span class="font-mono text-[10px]
                                                 text-slate-500 bg-slate-50
                                                 px-2 py-1 rounded-lg">

                                        {{ $variant->sku }}

                                    </span>

                                </td>

                                <td class="py-3.5 px-4 text-slate-600">

                                    {{ $variant->product?->category?->name ?? '-' }}

                                </td>

                                <td class="py-3.5 px-4 text-right">

                                    <span class="font-bold text-slate-900">
                                        {{ rupiah($variant->price) }}
                                    </span>

                                </td>

                                <td class="py-3.5 px-5 text-right">

                                    <span @class([
                                        'inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[10px] font-black',
                                        'bg-rose-100 text-rose-700' => $variant->stock === 0,
                                        'bg-amber-100 text-amber-700' => $variant->stock > 0 && $variant->stock <= 3,
                                        'bg-orange-100 text-orange-700' => $variant->stock > 3,
                                    ])>

                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>

                                        {{ $variant->stock }} unit

                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="flex flex-col items-center py-14">

                <div class="w-14 h-14 rounded-2xl bg-emerald-50
                            flex items-center justify-center mb-3">

                    <svg class="w-7 h-7 text-emerald-500"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0
                                 11-18 0 9 9 0 0118 0z"/>

                    </svg>

                </div>

                <p class="text-sm font-black text-slate-700">
                    Semua stok aman
                </p>

                <p class="text-[11px] text-slate-500 mt-1">
                    Tidak ada produk dengan stok di bawah batas kritis.
                </p>

            </div>

        @endif

    </div>


    {{-- =========================================================
         DEAD STOCK
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">

            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3">

                <div>

                    <div class="flex items-center gap-2">

                        <h3 class="text-sm font-black text-slate-900">
                            Produk Mati
                        </h3>

                        <span class="px-2 py-0.5 rounded-md bg-amber-50
                                     text-amber-600 text-[9px] font-black">
                            90 HARI
                        </span>

                    </div>

                    <p class="text-[11px] text-slate-500 mt-1">
                        {{ $deadStock->count() }}
                        varian memiliki stok tetapi tidak terjual
                        dalam 90 hari terakhir.
                    </p>

                </div>

                @if($deadStock->count() > 0)

                    <span class="px-3 py-1.5 rounded-lg bg-amber-50
                                 text-[10px] font-black text-amber-600">

                        PERLU EVALUASI

                    </span>

                @endif

            </div>

        </div>


        @if($deadStock->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full text-xs">

                    <thead class="bg-slate-50 border-b border-slate-100">

                        <tr class="text-[9px] uppercase tracking-wider
                                   font-black text-slate-400">

                            <th class="py-3.5 px-5 text-left">
                                Produk / Varian
                            </th>

                            <th class="py-3.5 px-4 text-left">
                                SKU
                            </th>

                            <th class="py-3.5 px-4 text-left">
                                Merek
                            </th>

                            <th class="py-3.5 px-4 text-right">
                                Stok
                            </th>

                            <th class="py-3.5 px-5 text-right">
                                Nilai Stok
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($deadStock->take(20) as $variant)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="py-3.5 px-5">

                                    <div class="flex items-center gap-3">

                                        <div class="w-9 h-9 rounded-lg bg-amber-50
                                                    text-amber-600 flex items-center
                                                    justify-center shrink-0">

                                            <svg class="w-4 h-4"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M12 8v4l3 3m6-3a9 9 0
                                                         11-18 0 9 9 0 0118 0z"/>

                                            </svg>

                                        </div>

                                        <div class="min-w-0">

                                            <p class="font-bold text-slate-900
                                                      truncate max-w-[220px]">

                                                {{ $variant->product?->name }}

                                            </p>

                                            <p class="text-[10px] text-slate-400 mt-0.5">
                                                {{ $variant->name }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="py-3.5 px-4">

                                    <span class="font-mono text-[10px]
                                                 text-slate-500">

                                        {{ $variant->sku }}

                                    </span>

                                </td>

                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $variant->product?->brand?->name ?? '-' }}
                                </td>

                                <td class="py-3.5 px-4 text-right">

                                    <span class="font-black text-slate-900">
                                        {{ number_format($variant->stock) }}
                                    </span>

                                    <span class="text-[9px] text-slate-400">
                                        unit
                                    </span>

                                </td>

                                <td class="py-3.5 px-5 text-right">

                                    <span class="font-black text-amber-600">
                                        {{ rupiah($variant->stock * $variant->price) }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="flex flex-col items-center py-12">

                <div class="w-12 h-12 rounded-2xl bg-emerald-50
                            flex items-center justify-center mb-3">

                    <svg class="w-6 h-6 text-emerald-500"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9
                                 0 11-18 0 9 9 0 0118 0z"/>

                    </svg>

                </div>

                <p class="text-xs font-black text-slate-600">
                    Tidak ada dead stock
                </p>

                <p class="text-[10px] text-slate-400 mt-1">
                    Semua produk masih memiliki pergerakan penjualan.
                </p>

            </div>

        @endif

    </div>


    {{-- =========================================================
         STOCK MOVEMENT
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        {{-- Section Header --}}
        <div class="p-5 border-b border-slate-100">

            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3">

                <div>

                    <div class="flex items-center gap-2">

                        <div class="w-8 h-8 rounded-lg bg-blue-50
                                    text-[#0B5CFF] flex items-center
                                    justify-center">

                            <svg class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M4 7h16M4 12h16M4 17h10"/>

                            </svg>

                        </div>

                        <h3 class="text-sm font-black text-slate-900">
                            Riwayat Mutasi Stok
                        </h3>

                    </div>

                    <p class="text-[11px] text-slate-500 mt-2">
                        Pantau seluruh perubahan stok berdasarkan
                        tipe, gudang, dan periode.
                    </p>

                </div>

                <span class="px-3 py-1.5 rounded-lg bg-slate-100
                             text-[10px] font-black text-slate-500">
                    {{ $movements->total() }} DATA
                </span>

            </div>

        </div>


        {{-- Filter --}}
        <div class="p-5 bg-slate-50/70 border-b border-slate-100">

            <form method="GET">

                <div class="grid grid-cols-1 sm:grid-cols-2
                            lg:grid-cols-5 gap-3 items-end">

                    {{-- Type --}}
                    <div>

                        <label class="block text-[10px] font-black
                                      uppercase tracking-wider
                                      text-slate-500 mb-1.5">

                            Tipe Mutasi

                        </label>

                        <select
                            name="type"
                            class="w-full h-10 border border-slate-200
                                   bg-white rounded-xl text-xs font-semibold
                                   text-slate-700 px-3
                                   focus:outline-none
                                   focus:border-[#0B5CFF]
                                   focus:ring-4 focus:ring-[#0B5CFF]/10
                                   transition">

                            <option value="">Semua Tipe</option>

                            @foreach([
                                'purchase',
                                'sale',
                                'return',
                                'adjustment',
                                'transfer',
                                'damage',
                                'reservation',
                                'release'
                            ] as $t)

                                <option
                                    value="{{ $t }}"
                                    @selected(($movementFilters['type'] ?? '') === $t)>
                                    {{ ucfirst($t) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Warehouse --}}
                    <div>

                        <label class="block text-[10px] font-black
                                      uppercase tracking-wider
                                      text-slate-500 mb-1.5">

                            Gudang

                        </label>

                        <select
                            name="warehouse_id"
                            class="w-full h-10 border border-slate-200
                                   bg-white rounded-xl text-xs font-semibold
                                   text-slate-700 px-3
                                   focus:outline-none
                                   focus:border-[#0B5CFF]
                                   focus:ring-4 focus:ring-[#0B5CFF]/10
                                   transition">

                            <option value="">
                                Semua Gudang
                            </option>

                            @foreach($warehouses as $wh)

                                <option
                                    value="{{ $wh->id }}"
                                    @selected(($movementFilters['warehouse_id'] ?? '') == $wh->id)>
                                    {{ $wh->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From --}}
                    <div>

                        <label class="block text-[10px] font-black
                                      uppercase tracking-wider
                                      text-slate-500 mb-1.5">

                            Dari

                        </label>

                        <input
                            type="date"
                            name="from"
                            value="{{ $movementFilters['from'] ?? '' }}"
                            class="w-full h-10 border border-slate-200
                                   bg-white rounded-xl text-xs font-semibold
                                   text-slate-700 px-3
                                   focus:outline-none
                                   focus:border-[#0B5CFF]
                                   focus:ring-4 focus:ring-[#0B5CFF]/10
                                   transition">

                    </div>


                    {{-- To --}}
                    <div>

                        <label class="block text-[10px] font-black
                                      uppercase tracking-wider
                                      text-slate-500 mb-1.5">

                            Sampai

                        </label>

                        <input
                            type="date"
                            name="to"
                            value="{{ $movementFilters['to'] ?? '' }}"
                            class="w-full h-10 border border-slate-200
                                   bg-white rounded-xl text-xs font-semibold
                                   text-slate-700 px-3
                                   focus:outline-none
                                   focus:border-[#0B5CFF]
                                   focus:ring-4 focus:ring-[#0B5CFF]/10
                                   transition">

                    </div>


                    {{-- Actions --}}
                    <div class="flex gap-2">

                        <button
                            type="submit"
                            class="h-10 flex-1 px-4 inline-flex
                                   items-center justify-center gap-2
                                   bg-[#0B5CFF] text-white rounded-xl
                                   text-xs font-black
                                   hover:bg-[#063B9E]
                                   transition">

                            <svg class="w-3.5 h-3.5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M21 21l-4.35-4.35m1.35-5.65
                                         a7 7 0 11-14 0 7 7 0 0114 0z"/>

                            </svg>

                            Filter

                        </button>

                        <a
                            href="{{ route('admin.laporan.inventaris') }}"
                            class="h-10 px-4 inline-flex items-center
                                   justify-center border border-slate-200
                                   bg-white text-slate-600 rounded-xl
                                   text-xs font-bold hover:bg-slate-50
                                   transition">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- Movement Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 border-b border-slate-100">

                    <tr class="text-[9px] uppercase tracking-wider
                               font-black text-slate-400">

                        <th class="py-3.5 px-5 text-left">
                            Waktu
                        </th>

                        <th class="py-3.5 px-4 text-left">
                            Produk / SKU
                        </th>

                        <th class="py-3.5 px-4 text-left">
                            Tipe
                        </th>

                        <th class="py-3.5 px-4 text-right">
                            Perubahan
                        </th>

                        <th class="py-3.5 px-4 text-right">
                            Stok Akhir
                        </th>

                        <th class="py-3.5 px-4 text-left">
                            Catatan
                        </th>

                        <th class="py-3.5 px-5 text-left">
                            Oleh
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($movements as $mov)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- Time --}}
                            <td class="py-3.5 px-5">

                                <div class="flex items-center gap-2">

                                    <div class="w-7 h-7 rounded-lg bg-slate-100
                                                flex items-center justify-center">

                                        <svg class="w-3.5 h-3.5 text-slate-500"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0
                                                     11-18 0 9 9 0 0118 0z"/>

                                        </svg>

                                    </div>

                                    <span class="text-[10px] font-semibold
                                                 text-slate-500 whitespace-nowrap">

                                        {{ tgl_indo($mov->created_at) }}

                                    </span>

                                </div>

                            </td>


                            {{-- Product --}}
                            <td class="py-3.5 px-4">

                                <p class="font-bold text-slate-900
                                          truncate max-w-[180px]">

                                    {{ $mov->productVariant?->product?->name }}

                                </p>

                                <p class="text-[10px] text-slate-400 mt-0.5">

                                    {{ $mov->productVariant?->sku }}

                                </p>

                            </td>


                            {{-- Type --}}
                            <td class="py-3.5 px-4">

                                <span @class([
                                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[9px] font-black',
                                    'bg-emerald-50 text-emerald-700' => in_array($mov->type, ['purchase','return','release']),
                                    'bg-rose-50 text-rose-700' => in_array($mov->type, ['sale','damage']),
                                    'bg-amber-50 text-amber-700' => in_array($mov->type, ['adjustment','transfer','reservation']),
                                ])>

                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>

                                    {{ ucfirst($mov->type) }}

                                </span>

                            </td>


                            {{-- Change --}}
                            <td class="py-3.5 px-4 text-right">

                                <span class="inline-flex items-center
                                             justify-end min-w-[55px]
                                             font-black
                                             {{ $mov->quantity_change > 0
                                                ? 'text-emerald-600'
                                                : 'text-rose-600' }}">

                                    {{ $mov->quantity_change > 0 ? '+' : '' }}
                                    {{ number_format($mov->quantity_change) }}

                                </span>

                            </td>


                            {{-- Final Stock --}}
                            <td class="py-3.5 px-4 text-right">

                                <span class="font-black text-slate-900">
                                    {{ number_format($mov->quantity_after) }}
                                </span>

                                <span class="text-[9px] text-slate-400">
                                    unit
                                </span>

                            </td>


                            {{-- Notes --}}
                            <td class="py-3.5 px-4">

                                <span
                                    title="{{ $mov->notes ?? '-' }}"
                                    class="block max-w-[180px]
                                           truncate text-slate-500">

                                    {{ $mov->notes ?? '-' }}

                                </span>

                            </td>


                            {{-- User --}}
                            <td class="py-3.5 px-5">

                                <div class="flex items-center gap-2">

                                    <div class="w-7 h-7 rounded-full bg-blue-50
                                                text-[#0B5CFF] flex items-center
                                                justify-center text-[9px]
                                                font-black">

                                        {{ strtoupper(substr($mov->user?->name ?? 'S', 0, 1)) }}

                                    </div>

                                    <span class="text-[10px] font-semibold
                                                 text-slate-600 whitespace-nowrap">

                                        {{ $mov->user?->name ?? 'Sistem' }}

                                    </span>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="py-14 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="w-14 h-14 rounded-2xl bg-slate-100
                                                flex items-center justify-center mb-3">

                                        <svg class="w-7 h-7 text-slate-400"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 17v-2m3 2v-4m3
                                                     4v-6m2 10H5a2 2 0
                                                     01-2-2V5a2 2 0
                                                     012-2h14a2 2 0
                                                     012 2v12a2 2
                                                     01-2 2z"/>

                                        </svg>

                                    </div>

                                    <p class="text-xs font-black text-slate-600">
                                        Tidak ada riwayat mutasi
                                    </p>

                                    <p class="text-[10px] text-slate-400 mt-1">
                                        Belum ada aktivitas stok sesuai filter yang dipilih.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($movements->hasPages())

            <div class="p-4 border-t border-slate-100 bg-slate-50/50">

                {{ $movements->links() }}

            </div>

        @endif

    </div>

</div>
@endsection
