@extends('layouts.admin')

@section('header_title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-4">

        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-[#0B5CFF]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3v18h18M7 16l4-5 3 3 5-7"/>
                    </svg>
                </span>

                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400">
                    Analytics
                </span>
            </div>

            <h2 class="text-2xl font-black tracking-tight text-slate-900">
                Laporan Penjualan
            </h2>

            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Analisis pendapatan, produk terlaris, performa kategori,
                merek, dan tren penjualan dalam satu periode.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl
                         bg-emerald-50 border border-emerald-100
                         text-[11px] font-bold text-emerald-700">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Data diperbarui
            </span>

            <span class="px-3 py-2 rounded-xl bg-slate-100 text-[11px] font-bold text-slate-600">
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>

    </div>


    {{-- =========================================================
         FILTER PANEL
    ========================================================== --}}
    <form method="GET"
          class="relative overflow-hidden bg-white rounded-2xl border border-slate-200
                 shadow-sm">

        <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#0B5CFF]"></div>

        <div class="p-5">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">

                {{-- Filter Info --}}
                <div class="lg:w-52 shrink-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0B5CFF] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 12.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 17.999v-5.585L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-black text-slate-900">
                                Filter Laporan
                            </p>
                            <p class="text-[10px] text-slate-500">
                                Pilih periode analisis
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Dari --}}
                <div class="flex-1">
                    <label class="block text-[10px] font-black uppercase tracking-wider
                                  text-slate-500 mb-1.5">
                        Dari Tanggal
                    </label>

                    <div class="relative">
                        <input
                            type="date"
                            name="dari"
                            value="{{ $from->toDateString() }}"
                            class="w-full h-11 border border-slate-200 bg-slate-50
                                   rounded-xl text-xs font-semibold text-slate-700 px-3
                                   focus:outline-none focus:bg-white
                                   focus:border-[#0B5CFF]
                                   focus:ring-4 focus:ring-[#0B5CFF]/10
                                   transition">
                    </div>
                </div>

                {{-- Sampai --}}
                <div class="flex-1">
                    <label class="block text-[10px] font-black uppercase tracking-wider
                                  text-slate-500 mb-1.5">
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        name="sampai"
                        value="{{ $to->toDateString() }}"
                        class="w-full h-11 border border-slate-200 bg-slate-50
                               rounded-xl text-xs font-semibold text-slate-700 px-3
                               focus:outline-none focus:bg-white
                               focus:border-[#0B5CFF]
                               focus:ring-4 focus:ring-[#0B5CFF]/10
                               transition">
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="h-11 px-5 inline-flex items-center justify-center gap-2
                               bg-[#0B5CFF] text-white rounded-xl text-xs font-black
                               hover:bg-[#063B9E] shadow-sm hover:shadow-md
                               transition">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>

                        Terapkan
                    </button>

                    <a
                        href="{{ route('admin.laporan.penjualan') }}"
                        class="h-11 px-5 inline-flex items-center justify-center
                               border border-slate-200 bg-white text-slate-600
                               rounded-xl text-xs font-bold hover:bg-slate-50
                               transition">
                        Reset
                    </a>
                </div>

            </div>
        </div>
    </form>


    {{-- =========================================================
         SUMMARY / KPI
    ========================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Revenue --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-slate-200
                    shadow-sm p-5">

            <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full bg-blue-50"></div>

            <div class="relative">
                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.12em]
                                  text-slate-500">
                            Total Pendapatan
                        </p>

                        <p class="text-2xl sm:text-3xl font-black tracking-tight
                                  text-slate-900 mt-2">
                            {{ rupiah($totalRevenue) }}
                        </p>

                        <p class="text-[10px] text-slate-500 mt-1">
                            {{ tgl_indo($from) }} — {{ tgl_indo($to) }}
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B5CFF]
                                flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                                     3 .895 3 2-1.343 2-3 2m0-8c1.11 0
                                     2.08.402 2.599 1M12 8V7m0 1v8m0
                                     0v1m0-1c-1.11 0-2.08-.402-2.599-1
                                     M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#0B5CFF]"></span>
                    <span class="text-[10px] font-semibold text-slate-500">
                        Revenue berhasil dibayarkan
                    </span>
                </div>
            </div>
        </div>


        {{-- Orders --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.12em]
                              text-slate-500">
                        Total Pesanan
                    </p>

                    <p class="text-2xl sm:text-3xl font-black tracking-tight
                              text-slate-900 mt-2">
                        {{ number_format($salesSummary->sum('total_pesanan')) }}
                    </p>

                    <p class="text-[10px] text-slate-500 mt-1">
                        Pesanan berhasil bayar
                    </p>
                </div>

                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600
                            flex items-center justify-center">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>

                </div>
            </div>

            <div class="mt-4">
                <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full w-2/3 rounded-full bg-emerald-500"></div>
                </div>
            </div>

        </div>


        {{-- Average --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.12em]
                              text-slate-500">
                        Rata-rata / Hari
                    </p>

                    <p class="text-2xl sm:text-3xl font-black tracking-tight
                              text-slate-900 mt-2">
                        {{ rupiah($salesSummary->count() > 0 ? $totalRevenue / $salesSummary->count() : 0) }}
                    </p>

                    <p class="text-[10px] text-slate-500 mt-1">
                        Dari {{ $salesSummary->count() }} hari aktif
                    </p>
                </div>

                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600
                            flex items-center justify-center">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                </div>
            </div>

            <div class="mt-4 flex items-center gap-2">
                <span class="px-2 py-1 rounded-lg bg-purple-50 text-purple-600
                             text-[9px] font-black">
                    DAILY AVG
                </span>
            </div>

        </div>

    </div>


    {{-- =========================================================
         MONTHLY SALES TREND
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3">

                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-black text-slate-900">
                            Tren Penjualan Bulanan
                        </h3>

                        <span class="px-2 py-0.5 rounded-md bg-blue-50
                                     text-[#0B5CFF] text-[9px] font-black">
                            {{ now()->year }}
                        </span>
                    </div>

                    <p class="text-[11px] text-slate-500 mt-1">
                        Perbandingan pendapatan setiap bulan dalam satu tahun.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#0B5CFF]"></span>
                    <span class="text-[10px] font-semibold text-slate-500">
                        Pendapatan
                    </span>
                </div>

            </div>
        </div>

        @php
            $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            $monthlyMap = $monthlySales->pluck('total_pendapatan', 'bulan');
            $maxMonthly = $monthlyMap->max() ?: 1;
        @endphp

        <div class="p-5">

            <div class="flex items-end justify-between gap-2 h-48">

                @for($m = 1; $m <= 12; $m++)

                    @php
                        $val = $monthlyMap->get($m, 0);
                        $barH = $maxMonthly > 0 ? ($val / $maxMonthly) * 100 : 0;
                    @endphp

                    <div class="flex-1 flex flex-col items-center
                                justify-end h-full gap-2 group">

                        <div class="w-full flex items-end justify-center h-full">

                            <div class="relative w-full max-w-12
                                        rounded-t-xl transition-all duration-300
                                        group-hover:opacity-80"
                                 style="
                                    height: {{ max($barH, $val > 0 ? 8 : 3) }}%;
                                    background: {{ $val > 0
                                        ? 'linear-gradient(180deg, #0B5CFF, #063B9E)'
                                        : '#e2e8f0' }};
                                 ">

                                @if($val > 0)
                                    <div class="absolute -top-7 left-1/2 -translate-x-1/2
                                                whitespace-nowrap opacity-0
                                                group-hover:opacity-100 transition
                                                px-2 py-1 rounded-lg bg-slate-900
                                                text-white text-[9px] font-bold">
                                        {{ rupiah($val) }}
                                    </div>
                                @endif

                            </div>

                        </div>

                        <span class="text-[9px] font-bold text-slate-400">
                            {{ $monthNames[$m - 1] }}
                        </span>

                    </div>

                @endfor

            </div>

        </div>
    </div>


    {{-- =========================================================
         TOP PRODUCTS + CATEGORY
    ========================================================== --}}
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

        {{-- TOP PRODUCTS --}}
        <div class="xl:col-span-3 bg-white rounded-2xl border border-slate-200
                    shadow-sm overflow-hidden">

            <div class="p-5 border-b border-slate-100">
                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="text-sm font-black text-slate-900">
                            Produk Terlaris
                        </h3>

                        <p class="text-[11px] text-slate-500 mt-1">
                            Top 10 produk berdasarkan jumlah unit terjual.
                        </p>
                    </div>

                    <span class="px-2.5 py-1 rounded-lg bg-blue-50
                                 text-[#0B5CFF] text-[9px] font-black">
                        TOP 10
                    </span>

                </div>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-xs">

                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                            <th class="py-3 px-5 text-left">Rank</th>
                            <th class="py-3 px-3 text-left">Produk</th>
                            <th class="py-3 px-3 text-right">Qty</th>
                            <th class="py-3 px-5 text-right">Revenue</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($topProducts as $i => $item)

                            <tr class="group hover:bg-slate-50 transition">

                                <td class="py-3 px-5">

                                    @if($i < 3)
                                        <span class="inline-flex items-center justify-center
                                                     w-7 h-7 rounded-lg bg-blue-50
                                                     text-[#0B5CFF] text-[10px] font-black">
                                            {{ $i + 1 }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-black text-slate-400 pl-2">
                                            {{ $i + 1 }}
                                        </span>
                                    @endif

                                </td>

                                <td class="py-3 px-3">
                                    <p class="font-bold text-slate-900 truncate max-w-[220px]">
                                        {{ $item->product_name }}
                                    </p>

                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $item->variant_name }}
                                        <span class="mx-1">•</span>
                                        {{ $item->sku }}
                                    </p>
                                </td>

                                <td class="py-3 px-3 text-right">
                                    <span class="font-black text-slate-900">
                                        {{ number_format($item->total_qty) }}
                                    </span>
                                    <span class="text-[9px] text-slate-400">
                                        unit
                                    </span>
                                </td>

                                <td class="py-3 px-5 text-right">
                                    <span class="font-black text-[#0B5CFF]">
                                        {{ rupiah($item->total_revenue) }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="py-12 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="w-12 h-12 rounded-2xl bg-slate-100
                                                    flex items-center justify-center mb-3">

                                            <svg class="w-6 h-6 text-slate-400"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                            </svg>

                                        </div>

                                        <p class="text-xs font-bold text-slate-500">
                                            Belum ada data penjualan
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>


        {{-- CATEGORY --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200
                    shadow-sm overflow-hidden">

            <div class="p-5 border-b border-slate-100">

                <h3 class="text-sm font-black text-slate-900">
                    Penjualan per Kategori
                </h3>

                <p class="text-[11px] text-slate-500 mt-1">
                    Distribusi revenue berdasarkan kategori produk.
                </p>

            </div>

            @php
                $totalCat = $salesByCategory->sum('total_revenue') ?: 1;
            @endphp

            <div class="p-5 space-y-5">

                @forelse($salesByCategory as $index => $cat)

                    @php
                        $pct = round(($cat->total_revenue / $totalCat) * 100, 1);
                    @endphp

                    <div>

                        <div class="flex items-center justify-between gap-3 mb-2">

                            <div class="min-w-0">
                                <p class="text-xs font-black text-slate-700 truncate">
                                    {{ $cat->kategori }}
                                </p>

                                <p class="text-[9px] text-slate-400 mt-0.5">
                                    {{ rupiah($cat->total_revenue) }}
                                </p>
                            </div>

                            <span class="shrink-0 text-[10px] font-black text-slate-600">
                                {{ $pct }}%
                            </span>

                        </div>

                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">

                            <div
                                class="h-full rounded-full bg-gradient-to-r
                                       from-[#0B5CFF] to-[#063B9E]
                                       transition-all duration-500"
                                style="width: {{ $pct }}%">
                            </div>

                        </div>

                    </div>

                @empty

                    <div class="py-10 text-center">

                        <p class="text-xs font-bold text-slate-400">
                            Belum ada data kategori.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- =========================================================
         BRAND PERFORMANCE
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="text-sm font-black text-slate-900">
                        Performa Merek
                    </h3>

                    <p class="text-[11px] text-slate-500 mt-1">
                        Perbandingan penjualan berdasarkan merek.
                    </p>
                </div>

                <span class="px-2.5 py-1 rounded-lg bg-slate-100
                             text-[9px] font-black text-slate-500">
                    BRAND
                </span>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 border-b border-slate-100">

                    <tr class="text-[9px] uppercase tracking-wider
                               font-black text-slate-400">

                        <th class="py-3.5 px-5 text-left">Merek</th>
                        <th class="py-3.5 px-5 text-right">Qty Terjual</th>
                        <th class="py-3.5 px-5 text-right">Total Revenue</th>
                        <th class="py-3.5 px-5 text-right">Proporsi</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @php
                        $totalBrand = $salesByBrand->sum('total_revenue') ?: 1;
                    @endphp

                    @forelse($salesByBrand as $brand)

                        @php
                            $brandPct = round(
                                ($brand->total_revenue / $totalBrand) * 100,
                                1
                            );
                        @endphp

                        <tr class="hover:bg-slate-50 transition">

                            <td class="py-3.5 px-5">

                                <div class="flex items-center gap-3">

                                    <div class="w-8 h-8 rounded-lg bg-slate-100
                                                flex items-center justify-center
                                                text-[10px] font-black text-slate-500">
                                        {{ strtoupper(substr($brand->merek, 0, 1)) }}
                                    </div>

                                    <span class="font-bold text-slate-900">
                                        {{ $brand->merek }}
                                    </span>

                                </div>

                            </td>

                            <td class="py-3.5 px-5 text-right font-semibold text-slate-700">
                                {{ number_format($brand->total_qty) }}
                            </td>

                            <td class="py-3.5 px-5 text-right">

                                <span class="font-black text-[#0B5CFF]">
                                    {{ rupiah($brand->total_revenue) }}
                                </span>

                            </td>

                            <td class="py-3.5 px-5 text-right">

                                <div class="inline-flex items-center gap-2">

                                    <div class="hidden sm:block w-16 h-1.5
                                                rounded-full bg-slate-100 overflow-hidden">

                                        <div
                                            class="h-full rounded-full bg-[#0B5CFF]"
                                            style="width: {{ $brandPct }}%">
                                        </div>

                                    </div>

                                    <span class="text-[10px] font-black text-slate-600">
                                        {{ $brandPct }}%
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="text-center py-12 text-xs text-slate-400">
                                Belum ada data penjualan merek.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         DAILY SALES
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">

            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3">

                <div>
                    <h3 class="text-sm font-black text-slate-900">
                        Rincian Penjualan Harian
                    </h3>

                    <p class="text-[11px] text-slate-500 mt-1">
                        Detail performa penjualan setiap hari dalam periode terpilih.
                    </p>
                </div>

                <div class="px-3 py-1.5 rounded-lg bg-blue-50
                            text-[10px] font-black text-[#0B5CFF]">
                    {{ $salesSummary->count() }} HARI AKTIF
                </div>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 border-b border-slate-100">

                    <tr class="text-[9px] uppercase tracking-wider
                               font-black text-slate-400">

                        <th class="py-3.5 px-5 text-left">Tanggal</th>
                        <th class="py-3.5 px-5 text-right">Jumlah Pesanan</th>
                        <th class="py-3.5 px-5 text-right">Total Pendapatan</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($salesSummary as $day)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="py-3.5 px-5">

                                <div class="flex items-center gap-3">

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
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>

                                    </div>

                                    <span class="font-bold text-slate-900">
                                        {{ tgl_indo(\Carbon\Carbon::parse($day->tanggal)) }}
                                    </span>

                                </div>

                            </td>

                            <td class="py-3.5 px-5 text-right">

                                <span class="inline-flex px-2.5 py-1 rounded-lg
                                             bg-slate-100 text-slate-700
                                             text-[10px] font-black">
                                    {{ number_format($day->total_pesanan) }} pesanan
                                </span>

                            </td>

                            <td class="py-3.5 px-5 text-right">

                                <span class="font-black text-[#0B5CFF]">
                                    {{ rupiah($day->total_pendapatan) }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3" class="py-14 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="w-12 h-12 rounded-2xl bg-slate-100
                                                flex items-center justify-center mb-3">

                                        <svg class="w-6 h-6 text-slate-400"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"/>

                                        </svg>

                                    </div>

                                    <p class="text-xs font-bold text-slate-500">
                                        Tidak ada data penjualan
                                    </p>

                                    <p class="text-[10px] text-slate-400 mt-1">
                                        Coba pilih periode tanggal yang berbeda.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                @if($salesSummary->count() > 0)

                    <tfoot class="bg-slate-50 border-t border-slate-200">

                        <tr>

                            <td class="py-4 px-5">

                                <span class="text-[10px] font-black uppercase
                                             tracking-wider text-slate-700">
                                    Total
                                </span>

                            </td>

                            <td class="py-4 px-5 text-right">

                                <span class="text-xs font-black text-slate-900">
                                    {{ number_format($salesSummary->sum('total_pesanan')) }}
                                </span>

                            </td>

                            <td class="py-4 px-5 text-right">

                                <span class="text-sm font-black text-[#0B5CFF]">
                                    {{ rupiah($totalRevenue) }}
                                </span>

                            </td>

                        </tr>

                    </tfoot>

                @endif

            </table>

        </div>

    </div>

</div>
@endsection

