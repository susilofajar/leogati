@extends('layouts.admin')

@section('header_title', 'Purchase Order (Pembelian)')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h2 class="text-xl font-extrabold text-slate-900">
                Pembelian (Purchase Orders)
            </h2>

            <p class="text-xs text-slate-500 mt-1">
                Kelola pesanan pembelian ke supplier dan proses penerimaan barang.
            </p>
        </div>

        <a href="{{ route('admin.pembelian.create') }}"
           class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5 self-start sm:self-auto">

            <svg class="w-4 h-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>

            Buat PO Baru
        </a>

    </div>


    {{-- FILTER --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">

        <form method="GET"
              action="{{ route('admin.pembelian.index') }}"
              class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

            {{-- SEARCH --}}
            <div class="md:col-span-6">

                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                    Cari Nomor PO / Supplier
                </label>

                <div class="relative">

                    <input type="text"
                           name="cari"
                           value="{{ request('cari') }}"
                           placeholder="PO-20260819-... atau nama supplier"
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition">

                    <div class="absolute left-3 top-2.5 text-slate-400">

                        <svg class="w-4 h-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>

                    </div>

                </div>

            </div>


            {{-- STATUS --}}
            <div class="md:col-span-4">

                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                    Status PO
                </label>

                <select name="status"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition">

                    <option value="">Semua Status</option>

                    <option value="draft"
                        {{ request('status') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="sent"
                        {{ request('status') == 'sent' ? 'selected' : '' }}>
                        Dikirim ke Supplier
                    </option>

                    <option value="partial"
                        {{ request('status') == 'partial' ? 'selected' : '' }}>
                        Diterima Sebagian
                    </option>

                    <option value="received"
                        {{ request('status') == 'received' ? 'selected' : '' }}>
                        Diterima Lengkap
                    </option>

                    <option value="cancelled"
                        {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>

                </select>

            </div>


            {{-- FILTER BUTTON --}}
            <div class="md:col-span-2">

                <button type="submit"
                        class="w-full px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>

                    Filter

                </button>

            </div>

        </form>

    </div>


    {{-- PURCHASE ORDER TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>
                <h3 class="text-sm font-extrabold text-slate-900">
                    Daftar Purchase Order
                </h3>

                <p class="text-[11px] text-slate-500 mt-0.5">
                    Daftar pesanan pembelian yang tercatat dalam sistem.
                </p>
            </div>

            @if($purchaseOrders->total() > 0)

                <span class="self-start sm:self-auto px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                    {{ number_format($purchaseOrders->total()) }} PO
                </span>

            @endif

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-xs text-left">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">

                    <tr>

                        <th class="px-5 py-3.5 whitespace-nowrap">
                            Nomor PO
                        </th>

                        <th class="px-5 py-3.5 min-w-[220px]">
                            Supplier
                        </th>

                        <th class="px-5 py-3.5 whitespace-nowrap">
                            Gudang Tujuan
                        </th>

                        <th class="px-5 py-3.5 whitespace-nowrap">
                            Tanggal Dibuat
                        </th>

                        <th class="px-5 py-3.5 text-center whitespace-nowrap">
                            Status
                        </th>

                        <th class="px-5 py-3.5 text-right whitespace-nowrap">
                            Total Biaya
                        </th>

                        <th class="px-5 py-3.5 text-right">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($purchaseOrders as $po)

                        <tr class="hover:bg-slate-50/80 transition">

                            {{-- PO NUMBER --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-2.5">

                                    <div class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-[#0B5CFF] flex items-center justify-center">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0118 8.414V19a2 2 0 01-2 2z"/>
                                        </svg>

                                    </div>

                                    <span class="font-mono font-extrabold text-[#0B5CFF] whitespace-nowrap">
                                        {{ $po->po_number }}
                                    </span>

                                </div>

                            </td>


                            {{-- SUPPLIER --}}
                            <td class="px-5 py-4">

                                <div class="font-bold text-slate-900">
                                    {{ $po->supplier->name ?? '-' }}
                                </div>

                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    PIC:
                                    <span class="font-semibold text-slate-600">
                                        {{ $po->supplier->pic_name ?? '-' }}
                                    </span>
                                </div>

                            </td>


                            {{-- WAREHOUSE --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-2">

                                    <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center">

                                        <svg class="w-3.5 h-3.5 text-slate-500"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>
                                        </svg>

                                    </div>

                                    <span class="text-xs font-semibold text-slate-700 whitespace-nowrap">
                                        {{ $po->warehouse->name ?? '-' }}
                                    </span>

                                </div>

                            </td>


                            {{-- DATE --}}
                            <td class="px-5 py-4">

                                <div class="text-xs font-semibold text-slate-700 whitespace-nowrap">
                                    {{ tgl_indo($po->created_at) }}
                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">

                                @php
                                    $statusClasses = [
                                        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'danger' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'primary' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'secondary' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];

                                    $statusClass = $statusClasses[$po->status_color]
                                        ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusClass }} whitespace-nowrap">
                                    {{ $po->status_label }}
                                </span>

                            </td>


                            {{-- TOTAL --}}
                            <td class="px-5 py-4 text-right">

                                <span class="text-xs font-extrabold text-slate-900 whitespace-nowrap">
                                    {{ rupiah($po->total_amount) }}
                                </span>

                            </td>


                            {{-- ACTION --}}
                            <td class="px-5 py-4 text-right">

                                <a href="{{ route('admin.pembelian.show', $po->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-[#0B5CFF] text-slate-700 hover:text-white font-bold rounded-lg transition whitespace-nowrap">

                                    Detail

                                    <svg class="w-3.5 h-3.5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M9 5l7 7-7 7"/>
                                    </svg>

                                </a>

                            </td>

                        </tr>

                    @empty

                        {{-- EMPTY STATE --}}
                        <tr>

                            <td colspan="7" class="px-5 py-12 text-center">

                                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center">

                                    <svg class="w-7 h-7 text-slate-400"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1.8"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0118 8.414V19a2 2 0 01-2 2z"/>
                                    </svg>

                                </div>

                                <p class="text-sm font-bold text-slate-700">
                                    Belum Ada Purchase Order
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    Belum ada pesanan pembelian yang tercatat dalam sistem.
                                </p>

                                <a href="{{ route('admin.pembelian.create') }}"
                                   class="inline-flex items-center gap-1.5 mt-4 px-3.5 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition">

                                    <svg class="w-3.5 h-3.5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M12 4v16m8-8H4"/>
                                    </svg>

                                    Buat PO Baru

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($purchaseOrders->hasPages())

            <div class="px-5 py-4 border-t border-slate-100">
                {{ $purchaseOrders->links() }}
            </div>

        @endif

    </div>

</div>
@endsection