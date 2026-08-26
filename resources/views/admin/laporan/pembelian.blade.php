@extends('layouts.admin')

@section('header_title', 'Laporan Pembelian')

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
                              d="M9 14l6-6m2-4h3a2 2 0 012 2v3
                                 M19 6l-7.5 7.5a2.121 2.121 0
                                 01-3 0L6 11a2.121 2.121 0
                                 010-3L13.5.5"/>

                    </svg>

                </span>

                <span class="text-[10px] font-bold uppercase
                             tracking-[0.15em] text-slate-400">

                    Purchase Analytics

                </span>

            </div>

            <h2 class="text-2xl font-black tracking-tight text-slate-900">
                Laporan Pembelian & Supplier
            </h2>

            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Pantau riwayat purchase order, pengeluaran pembelian,
                serta kontribusi setiap supplier terhadap total pembelian.
            </p>

        </div>


        <div class="flex items-center gap-2">

            <span class="inline-flex items-center gap-2 px-3 py-2
                         rounded-xl bg-emerald-50 border border-emerald-100
                         text-[11px] font-bold text-emerald-700">

                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                Procurement Data Aktif

            </span>

            <span class="px-3 py-2 rounded-xl bg-slate-100
                         text-[11px] font-bold text-slate-600">

                {{ now()->translatedFormat('d F Y') }}

            </span>

        </div>

    </div>


    {{-- =========================================================
         SUPPLIER ANALYSIS
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">

            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3">

                <div class="flex items-start gap-3">

                    <div class="w-10 h-10 rounded-xl bg-blue-50
                                text-[#0B5CFF] flex items-center
                                justify-center shrink-0">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 10h18M5 6h14a2 2 0
                                     012 2v10a2 2 0 01-2 2H5a2
                                     2 0 01-2-2V8a2 2 0 012-2z
                                     M8 14h2m4 0h2"/>

                        </svg>

                    </div>

                    <div>

                        <div class="flex items-center gap-2">

                            <h3 class="text-sm font-black text-slate-900">
                                Analisis Pengeluaran Supplier
                            </h3>

                            <span class="px-2 py-0.5 rounded-md
                                         bg-blue-50 text-[#0B5CFF]
                                         text-[9px] font-black">

                                PROCUREMENT

                            </span>

                        </div>

                        <p class="text-[11px] text-slate-500 mt-1">
                            Total nilai pembelian kumulatif setiap supplier,
                            tidak termasuk purchase order yang dibatalkan.
                        </p>

                    </div>

                </div>


                @if($supplierAnalysis->count() > 0)

                    <span class="inline-flex items-center gap-1.5
                                 px-3 py-1.5 rounded-lg bg-slate-100
                                 text-[10px] font-black text-slate-500">

                        {{ $supplierAnalysis->count() }} SUPPLIER

                    </span>

                @endif

            </div>

        </div>


        @php
            $totalSupplierVal = $supplierAnalysis->sum('total_nilai') ?: 1;
            $totalSupplierPO = $supplierAnalysis->sum('total_po');
        @endphp


        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 border-b border-slate-100">

                    <tr class="text-[9px] uppercase tracking-wider
                               font-black text-slate-400">

                        <th class="py-3.5 px-5 text-left">
                            Ranking
                        </th>

                        <th class="py-3.5 px-4 text-left">
                            Supplier
                        </th>

                        <th class="py-3.5 px-4 text-right">
                            Jumlah PO
                        </th>

                        <th class="py-3.5 px-4 text-right">
                            Total Pembelian
                        </th>

                        <th class="py-3.5 px-5 text-right">
                            Proporsi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($supplierAnalysis as $i => $s)

                        @php
                            $percentage = round(
                                ($s->total_nilai / $totalSupplierVal) * 100,
                                1
                            );
                        @endphp

                        <tr class="hover:bg-slate-50 transition">

                            {{-- Ranking --}}
                            <td class="py-3.5 px-5">

                                @if($i < 3)

                                    <span @class([
                                        'inline-flex w-8 h-8 rounded-lg items-center justify-center font-black text-[10px]',
                                        'bg-amber-100 text-amber-700' => $i === 0,
                                        'bg-slate-200 text-slate-700' => $i === 1,
                                        'bg-orange-100 text-orange-700' => $i === 2,
                                    ])>

                                        {{ $i + 1 }}

                                    </span>

                                @else

                                    <span class="inline-flex w-8 h-8
                                                 rounded-lg bg-slate-50
                                                 items-center justify-center
                                                 font-black text-[10px]
                                                 text-slate-400">

                                        {{ $i + 1 }}

                                    </span>

                                @endif

                            </td>


                            {{-- Supplier --}}
                            <td class="py-3.5 px-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-9 h-9 rounded-xl bg-blue-50
                                                text-[#0B5CFF]
                                                flex items-center justify-center
                                                text-[10px] font-black shrink-0">

                                        {{ strtoupper(substr($s->supplier, 0, 2)) }}

                                    </div>

                                    <div class="min-w-0">

                                        <p class="font-bold text-slate-900
                                                  truncate max-w-[220px]">

                                            {{ $s->supplier }}

                                        </p>

                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            Supplier
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- PO --}}
                            <td class="py-3.5 px-4 text-right">

                                <span class="inline-flex items-center
                                             px-2.5 py-1 rounded-lg
                                             bg-slate-100 text-slate-700
                                             font-black text-[10px]">

                                    {{ number_format($s->total_po) }}

                                </span>

                            </td>


                            {{-- Total --}}
                            <td class="py-3.5 px-4 text-right">

                                <span class="font-black text-[#0B5CFF]">

                                    {{ rupiah($s->total_nilai) }}

                                </span>

                            </td>


                            {{-- Percentage --}}
                            <td class="py-3.5 px-5">

                                <div class="flex items-center
                                            justify-end gap-2">

                                    <div class="w-20 bg-slate-100
                                                rounded-full h-1.5 overflow-hidden">

                                        <div
                                            class="h-1.5 rounded-full bg-[#0B5CFF]
                                                   transition-all"
                                            style="width: {{ min($percentage, 100) }}%">
                                        </div>

                                    </div>

                                    <span class="text-[10px] font-bold
                                                 text-slate-500 w-10 text-right">

                                        {{ $percentage }}%

                                    </span>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="py-14 text-center">

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
                                                  d="M3 10h18M5 6h14a2 2 0
                                                     012 2v10a2 2 0 01-2
                                                     2H5a2 2 0 01-2-2V8a2
                                                     2 0 012-2z"/>

                                        </svg>

                                    </div>

                                    <p class="text-xs font-black text-slate-600">
                                        Belum ada data supplier
                                    </p>

                                    <p class="text-[10px] text-slate-400 mt-1">
                                        Data pengeluaran supplier akan muncul
                                        setelah terdapat purchase order.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                @if($supplierAnalysis->count() > 0)

                    <tfoot class="bg-slate-50 border-t border-slate-200">

                        <tr>

                            <td colspan="2"
                                class="py-3.5 px-5">

                                <span class="text-[10px] font-black
                                             uppercase tracking-wider
                                             text-slate-500">

                                    Total Procurement

                                </span>

                            </td>

                            <td class="py-3.5 px-4 text-right">

                                <span class="text-xs font-black text-slate-900">

                                    {{ number_format($totalSupplierPO) }}

                                </span>

                            </td>

                            <td class="py-3.5 px-4 text-right">

                                <span class="text-xs font-black text-[#0B5CFF]">

                                    {{ rupiah($supplierAnalysis->sum('total_nilai')) }}

                                </span>

                            </td>

                            <td class="py-3.5 px-5 text-right">

                                <span class="text-[10px] font-black
                                             text-slate-400">

                                    100%

                                </span>

                            </td>

                        </tr>

                    </tfoot>

                @endif

            </table>

        </div>

    </div>


    {{-- =========================================================
         PURCHASE ORDER SECTION
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        {{-- Section Header --}}
        <div class="p-5 border-b border-slate-100">

            <div class="flex items-start gap-3">

                <div class="w-10 h-10 rounded-xl bg-emerald-50
                            text-emerald-600 flex items-center justify-center
                            shrink-0">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2
                                 2 0 002 2h10a2 2 0 002-2V7a2
                                 2 0 00-2-2h-2M9 5a2 2 0
                                 002 2h2a2 2 0 002-2M9 5a2 2
                                 0 012-2h2a2 2 0 012 2"/>

                    </svg>

                </div>

                <div>

                    <h3 class="text-sm font-black text-slate-900">
                        Riwayat Purchase Order
                    </h3>

                    <p class="text-[11px] text-slate-500 mt-1">
                        Lihat dan filter seluruh riwayat purchase order
                        berdasarkan supplier, status, dan periode.
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FILTER
        ====================================================== --}}
        <div class="p-5 bg-slate-50/70 border-b border-slate-100">

            <form method="GET">

                <div class="grid grid-cols-1 sm:grid-cols-2
                            lg:grid-cols-5 gap-3 items-end">

                    {{-- Supplier --}}
                    <div>

                        <label class="block text-[10px] font-black
                                      uppercase tracking-wider
                                      text-slate-500 mb-1.5">

                            Supplier

                        </label>

                        <select
                            name="supplier_id"
                            class="w-full h-10 border border-slate-200
                                   bg-white rounded-xl text-xs font-semibold
                                   text-slate-700 px-3
                                   focus:outline-none
                                   focus:border-[#0B5CFF]
                                   focus:ring-4 focus:ring-[#0B5CFF]/10
                                   transition">

                            <option value="">
                                Semua Supplier
                            </option>

                            @foreach($suppliers as $sup)

                                <option
                                    value="{{ $sup->id }}"
                                    @selected(($poFilters['supplier_id'] ?? '') == $sup->id)>

                                    {{ $sup->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div>

                        <label class="block text-[10px] font-black
                                      uppercase tracking-wider
                                      text-slate-500 mb-1.5">

                            Status

                        </label>

                        <select
                            name="status"
                            class="w-full h-10 border border-slate-200
                                   bg-white rounded-xl text-xs font-semibold
                                   text-slate-700 px-3
                                   focus:outline-none
                                   focus:border-[#0B5CFF]
                                   focus:ring-4 focus:ring-[#0B5CFF]/10
                                   transition">

                            <option value="">
                                Semua Status
                            </option>

                            @foreach(['draft','sent','partial','received','cancelled'] as $st)

                                <option
                                    value="{{ $st }}"
                                    @selected(($poFilters['status'] ?? '') === $st)>

                                    {{ ucfirst($st) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From --}}
                    <div>

                        <label class="block text-[10px] font-black
                                      uppercase tracking-wider
                                      text-slate-500 mb-1.5">

                            Dari Tanggal

                        </label>

                        <input
                            type="date"
                            name="from"
                            value="{{ $poFilters['from'] ?? '' }}"
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

                            Sampai Tanggal

                        </label>

                        <input
                            type="date"
                            name="to"
                            value="{{ $poFilters['to'] ?? '' }}"
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
                                   hover:bg-[#063B9E] transition">

                            <svg class="w-3.5 h-3.5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M21 21l-4.35-4.35m1.35-5.65
                                         a7 7 0 11-14 0 7 7 0
                                         0114 0z"/>

                            </svg>

                            Filter

                        </button>


                        <a
                            href="{{ route('admin.laporan.pembelian') }}"
                            class="h-10 px-4 inline-flex
                                   items-center justify-center
                                   border border-slate-200
                                   bg-white text-slate-600 rounded-xl
                                   text-xs font-bold
                                   hover:bg-slate-100 transition">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- =====================================================
             PURCHASE ORDER TABLE
        ====================================================== --}}
        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 border-b border-slate-100">

                    <tr class="text-[9px] uppercase tracking-wider
                               font-black text-slate-400">

                        <th class="py-3.5 px-5 text-left">
                            No. PO
                        </th>

                        <th class="py-3.5 px-4 text-left">
                            Supplier
                        </th>

                        <th class="py-3.5 px-4 text-left">
                            Tanggal Order
                        </th>

                        <th class="py-3.5 px-4 text-right">
                            Item
                        </th>

                        <th class="py-3.5 px-4 text-right">
                            Total Nilai
                        </th>

                        <th class="py-3.5 px-5 text-left">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($purchaseOrders as $po)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- PO Number --}}
                            <td class="py-3.5 px-5">

                                <a
                                    href="{{ route('admin.pembelian.show', $po->id) }}"
                                    class="inline-flex items-center gap-2
                                           group">

                                    <span class="w-8 h-8 rounded-lg
                                                 bg-blue-50 text-[#0B5CFF]
                                                 flex items-center justify-center">

                                        <svg class="w-3.5 h-3.5"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2
                                                     2 0 01-2-2V5a2 2
                                                     0 012-2h6l5 5v11a2
                                                     2 0 01-2 2z"/>

                                        </svg>

                                    </span>

                                    <span class="font-mono font-black
                                                 text-[#0B5CFF]
                                                 group-hover:underline">

                                        {{ $po->po_number }}

                                    </span>

                                </a>

                            </td>


                            {{-- Supplier --}}
                            <td class="py-3.5 px-4">

                                <div class="flex items-center gap-2.5">

                                    <div class="w-8 h-8 rounded-lg bg-slate-100
                                                text-slate-600
                                                flex items-center justify-center
                                                text-[9px] font-black">

                                        {{ strtoupper(substr($po->supplier?->name ?? '-', 0, 2)) }}

                                    </div>

                                    <span class="font-bold text-slate-900">

                                        {{ $po->supplier?->name ?? '-' }}

                                    </span>

                                </div>

                            </td>


                            {{-- Date --}}
                            <td class="py-3.5 px-4">

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
                                                  d="M8 7V3m8 4V3m-9 8h10
                                                     M5 21h14a2 2 0 002-2V7
                                                     a2 2 0 00-2-2H5a2 2
                                                     0 00-2 2v12a2 2 0
                                                     002 2z"/>

                                        </svg>

                                    </div>

                                    <span class="text-[10px] font-semibold
                                                 text-slate-500">

                                        {{ tgl_indo($po->order_date) }}

                                    </span>

                                </div>

                            </td>


                            {{-- Items --}}
                            <td class="py-3.5 px-4 text-right">

                                <span class="inline-flex items-center
                                             px-2.5 py-1 rounded-lg
                                             bg-slate-100 text-slate-700
                                             text-[10px] font-black">

                                    {{ $po->items->count() }}

                                </span>

                            </td>


                            {{-- Total --}}
                            <td class="py-3.5 px-4 text-right">

                                <span class="font-black text-slate-900">

                                    {{ rupiah($po->total_amount) }}

                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="py-3.5 px-5">

                                <span @class([
                                    'inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[10px] font-black',
                                    'bg-slate-100 text-slate-600' => $po->status === 'draft',
                                    'bg-blue-50 text-blue-700' => $po->status === 'sent',
                                    'bg-amber-50 text-amber-700' => $po->status === 'partial',
                                    'bg-emerald-50 text-emerald-700' => $po->status === 'received',
                                    'bg-rose-50 text-rose-700' => $po->status === 'cancelled',
                                ])>

                                    <span @class([
                                        'w-1.5 h-1.5 rounded-full',
                                        'bg-slate-400' => $po->status === 'draft',
                                        'bg-blue-500' => $po->status === 'sent',
                                        'bg-amber-500' => $po->status === 'partial',
                                        'bg-emerald-500' => $po->status === 'received',
                                        'bg-rose-500' => $po->status === 'cancelled',
                                    ])></span>

                                    {{ ucfirst($po->status) }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="py-14 text-center">

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
                                                  d="M9 12h6m-6 4h6m2 5H7a2
                                                     2 0 01-2-2V5a2 2
                                                     0 012-2h6l5 5v11a2
                                                     2 0 01-2 2z"/>

                                        </svg>

                                    </div>

                                    <p class="text-xs font-black text-slate-600">
                                        Tidak ada Purchase Order
                                    </p>

                                    <p class="text-[10px] text-slate-400 mt-1">
                                        Tidak ditemukan PO yang sesuai
                                        dengan filter yang dipilih.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($purchaseOrders->hasPages())

            <div class="p-4 border-t border-slate-100
                        bg-white">

                {{ $purchaseOrders->links() }}

            </div>

        @endif

    </div>

</div>
@endsection