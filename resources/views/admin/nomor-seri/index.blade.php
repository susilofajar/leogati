@extends('layouts.admin')

@section('header_title', 'Pelacakan Nomor Seri')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">
            Pelacakan Nomor Seri
        </h2>
        <p class="text-xs text-slate-500 mt-1">
            Pelacakan unit produk terdaftar, status garansi, dan riwayat transaksi.
        </p>
    </div>


    {{-- FILTER --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">

        <form method="GET"
              action="{{ route('admin.nomor_seri.index') }}"
              class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

            {{-- SEARCH --}}
            <div class="md:col-span-6">
                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                    Cari Nomor Seri / Nama Produk
                </label>

                <div class="relative">
                    <input type="text"
                           name="cari"
                           value="{{ request('cari') }}"
                           placeholder="Nomor seri unik atau nama produk..."
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
                    Status Unit
                </label>

                <select name="status"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition">

                    <option value="">Semua Status</option>

                    <option value="available"
                        {{ request('status') == 'available' ? 'selected' : '' }}>
                        Tersedia
                    </option>

                    <option value="reserved"
                        {{ request('status') == 'reserved' ? 'selected' : '' }}>
                        Direservasi
                    </option>

                    <option value="sold"
                        {{ request('status') == 'sold' ? 'selected' : '' }}>
                        Terjual
                    </option>

                    <option value="returned"
                        {{ request('status') == 'returned' ? 'selected' : '' }}>
                        Dikembalikan
                    </option>

                    <option value="damaged"
                        {{ request('status') == 'damaged' ? 'selected' : '' }}>
                        Rusak
                    </option>

                    <option value="warranty"
                        {{ request('status') == 'warranty' ? 'selected' : '' }}>
                        Klaim Garansi
                    </option>

                </select>
            </div>


            {{-- BUTTON --}}
            <div class="md:col-span-2">

                <button type="submit"
                        class="w-full px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5">

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


    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>
                <h3 class="text-sm font-extrabold text-slate-900">
                    Daftar Nomor Seri
                </h3>

                <p class="text-[11px] text-slate-500 mt-0.5">
                    Daftar unit produk yang tercatat dalam sistem inventaris.
                </p>
            </div>

            @if($serials->total() > 0)
                <span class="self-start sm:self-auto px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                    {{ number_format($serials->total()) }} Unit
                </span>
            @endif

        </div>


        {{-- TABLE CONTENT --}}
        <div class="overflow-x-auto">

            <table class="w-full text-xs text-left">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">

                    <tr>

                        <th class="px-5 py-3.5 whitespace-nowrap">
                            Nomor Seri
                        </th>

                        <th class="px-5 py-3.5 min-w-[260px]">
                            Produk / Varian
                        </th>

                        <th class="px-5 py-3.5 whitespace-nowrap">
                            Lokasi Gudang
                        </th>

                        <th class="px-5 py-3.5 text-center whitespace-nowrap">
                            Status
                        </th>

                        <th class="px-5 py-3.5 whitespace-nowrap">
                            Tanggal Beli / PO
                        </th>

                        <th class="px-5 py-3.5 whitespace-nowrap">
                            Garansi Berakhir
                        </th>

                        <th class="px-5 py-3.5 text-right">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($serials as $serial)

                        <tr class="hover:bg-slate-50/80 transition">

                            {{-- SERIAL NUMBER --}}
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
                                                  d="M15 5l4 4m0 0l-9.5 9.5L5 19l.5-4.5L15 5z"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M13 7l4 4"/>
                                        </svg>

                                    </div>

                                    <span class="font-mono font-extrabold text-[#0B5CFF] whitespace-nowrap">
                                        {{ $serial->serial_number }}
                                    </span>

                                </div>

                            </td>


                            {{-- PRODUCT --}}
                            <td class="px-5 py-4">

                                <div class="font-bold text-slate-900">
                                    {{ $serial->productVariant->product->name ?? '-' }}
                                </div>

                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $serial->productVariant->name }}
                                </div>

                                <div class="text-[10px] font-mono text-slate-400 mt-0.5">
                                    SKU: {{ $serial->productVariant->sku }}
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
                                        {{ $serial->warehouse->name ?? 'Gudang Pusat' }}
                                    </span>

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">

                                @php
                                    $statusClasses = [
                                        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'danger'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'primary' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'secondary' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];

                                    $statusClass = $statusClasses[$serial->status_color] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusClass }}">
                                    {{ $serial->status_label }}
                                </span>

                            </td>


                            {{-- PURCHASE --}}
                            <td class="px-5 py-4">

                                @if($serial->purchased_at)

                                    <div class="text-xs font-semibold text-slate-700 whitespace-nowrap">
                                        {{ tgl_indo($serial->purchased_at) }}
                                    </div>

                                @else

                                    <span class="text-xs text-slate-400">
                                        -
                                    </span>

                                @endif

                                @if($serial->purchaseOrder)

                                    <a href="{{ route('admin.pembelian.show', $serial->purchaseOrder->id) }}"
                                       class="inline-flex items-center gap-1 mt-1 text-[10px] font-bold text-[#0B5CFF] hover:text-[#063B9E] transition">

                                        <svg class="w-3 h-3"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0118 8.414V19a2 2 0 01-2 2z"/>
                                        </svg>

                                        #{{ $serial->purchaseOrder->po_number }}

                                    </a>

                                @endif

                            </td>


                            {{-- WARRANTY --}}
                            <td class="px-5 py-4">

                                @if($serial->warranty_expires_at)

                                    @if($serial->isUnderWarranty())

                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">

                                            <svg class="w-3 h-3"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.291 9 11.623C17.176 19.291 21 14.591 21 9c0-1.046-.133-2.061-.382-3.016z"/>
                                            </svg>

                                            {{ tgl_indo($serial->warranty_expires_at) }}

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">

                                            <svg class="w-3 h-3"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                                            </svg>

                                            {{ tgl_indo($serial->warranty_expires_at) }}

                                        </span>

                                    @endif

                                @else

                                    <span class="text-xs text-slate-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}
                            <td class="px-5 py-4 text-right">

                                <a href="{{ route('admin.nomor_seri.show', $serial->id) }}"
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
                                              d="M15 5l4 4m0 0l-9.5 9.5L5 19l.5-4.5L15 5z"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1.8"
                                              d="M13 7l4 4"/>
                                    </svg>

                                </div>

                                <p class="text-sm font-bold text-slate-700">
                                    Belum Ada Nomor Seri
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    Belum ada nomor seri yang terdaftar dalam sistem.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($serials->hasPages())

            <div class="px-5 py-4 border-t border-slate-100">
                {{ $serials->links() }}
            </div>

        @endif

    </div>

</div>
@endsection