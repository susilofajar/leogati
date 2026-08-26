@extends('layouts.admin')

@section('header_title', 'Detail Purchase Order')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

        <a href="{{ route('admin.pembelian.index') }}"
           class="w-10 h-10 shrink-0 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 transition flex items-center justify-center">

            <svg class="w-5 h-5 text-slate-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>

        </a>

        <div class="flex-1">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">

                <h2 class="text-xl font-extrabold text-slate-900">
                    Purchase Order
                </h2>

                <span class="inline-flex self-start px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-100 text-[#0B5CFF] text-xs font-bold font-mono">
                    #{{ $pembelian->po_number }}
                </span>

            </div>

            <p class="text-xs text-slate-500 mt-1">
                Dibuat oleh {{ $pembelian->creator->name ?? 'Admin' }}
                pada {{ tgl_indo($pembelian->created_at) }}
            </p>
        </div>

        <div>
            <span class="inline-flex items-center px-3 py-2 rounded-xl text-[10px] font-extrabold border
                @if($pembelian->status === 'draft')
                    bg-slate-100 text-slate-600 border-slate-200
                @elseif($pembelian->status === 'sent')
                    bg-blue-50 text-blue-700 border-blue-200
                @elseif($pembelian->status === 'partial')
                    bg-amber-50 text-amber-700 border-amber-200
                @elseif($pembelian->status === 'received')
                    bg-emerald-50 text-emerald-700 border-emerald-200
                @elseif($pembelian->status === 'cancelled')
                    bg-rose-50 text-rose-700 border-rose-200
                @else
                    bg-slate-100 text-slate-600 border-slate-200
                @endif
            ">
                {{ $pembelian->status_label }}
            </span>
        </div>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4">

            <div class="flex items-center gap-3">

                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>

                </div>

                <p class="text-xs font-semibold text-emerald-800">
                    {{ session('success') }}
                </p>

            </div>

        </div>

    @endif


    {{-- ERRORS --}}
    @if($errors->any())

        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4">

            <div class="flex items-start gap-3">

                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                    </svg>

                </div>

                <div>

                    <p class="text-xs font-extrabold text-rose-800">
                        Terdapat kesalahan
                    </p>

                    <ul class="mt-1 text-xs text-rose-700 list-disc list-inside space-y-0.5">

                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================== --}}
    {{-- INFORMATION CARDS --}}
    {{-- ============================================== --}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- SUPPLIER --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100">

                <div class="flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">

                        <svg class="w-4 h-4 text-[#0B5CFF]"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>
                        </svg>

                    </div>

                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">
                            Data Supplier
                        </h3>

                        <p class="text-[10px] text-slate-500">
                            Informasi pemasok barang.
                        </p>
                    </div>

                </div>

            </div>


            <div class="p-5">

                <h4 class="text-sm font-extrabold text-slate-900">
                    {{ $pembelian->supplier->name ?? '-' }}
                </h4>

                <div class="mt-1 text-[10px] font-mono text-slate-500">
                    KODE: {{ $pembelian->supplier->code ?? '-' }}
                </div>

                <div class="mt-4 space-y-2.5">

                    <div class="flex items-center gap-2.5 text-xs text-slate-600">

                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-slate-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>

                        <span>
                            PIC: <strong>{{ $pembelian->supplier->pic_name ?? '-' }}</strong>
                        </span>

                    </div>


                    <div class="flex items-center gap-2.5 text-xs text-slate-600">

                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-slate-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 5h2l2.5 6L5 13a16 16 0 006 6l2-2.5L19 19v-2a2 2 0 00-2-2h-1l-1.5 1.5a12 12 0 01-5-5L11 10V9a2 2 0 00-2-2H7"/>
                            </svg>
                        </div>

                        <span>
                            Telp: {{ $pembelian->supplier->phone ?? '-' }}
                        </span>

                    </div>


                    <div class="flex items-center gap-2.5 text-xs text-slate-600">

                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-slate-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                        </div>

                        <span class="truncate">
                            {{ $pembelian->supplier->email ?? '-' }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- WAREHOUSE --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100">

                <div class="flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-cyan-50 border border-cyan-100 flex items-center justify-center">

                        <svg class="w-4 h-4 text-cyan-600"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>

                    </div>

                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">
                            Gudang Tujuan
                        </h3>

                        <p class="text-[10px] text-slate-500">
                            Lokasi penerimaan barang.
                        </p>
                    </div>

                </div>

            </div>


            <div class="p-5">

                <h4 class="text-sm font-extrabold text-slate-900">
                    {{ $pembelian->warehouse->name ?? '-' }}
                </h4>

                <div class="mt-1 text-[10px] font-mono text-slate-500">
                    KODE: {{ $pembelian->warehouse->code ?? '-' }}
                </div>

                <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-100">

                    <p class="text-xs text-slate-700">
                        {{ $pembelian->warehouse->address ?? '-' }}
                    </p>

                    <p class="text-[11px] text-slate-500 mt-1">
                        {{ $pembelian->warehouse->city ?? '' }}
                        @if($pembelian->warehouse->city && $pembelian->warehouse->province)
                            ,
                        @endif
                        {{ $pembelian->warehouse->province ?? '' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- TRANSACTION --}}
        <div class="bg-slate-900 rounded-2xl shadow-2xs overflow-hidden relative">

            <div class="absolute -right-12 -top-12 w-36 h-36 rounded-full bg-blue-500/10"></div>

            <div class="relative p-5 h-full flex flex-col">

                <div class="flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center">

                        <svg class="w-4 h-4 text-emerald-300"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5-1.79 2.5-4 2.5-4-1.12-4-2.5M12 5c-4.42 0-8 1.34-8 3v8c0 1.66 3.58 3 8 3s8-1.34 8-3V8c0-1.66-3.58-3-8-3z"/>
                        </svg>

                    </div>

                    <div>
                        <h3 class="text-sm font-extrabold text-white">
                            Nilai Transaksi
                        </h3>

                        <p class="text-[10px] text-slate-400">
                            Total nilai Purchase Order.
                        </p>
                    </div>

                </div>


                <div class="mt-7">

                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Total Biaya PO
                    </p>

                    <div class="mt-1 text-2xl font-black text-white">
                        {{ rupiah($pembelian->total_amount) }}
                    </div>

                </div>


                @if($pembelian->status === 'draft')

                    <div class="mt-auto pt-6 flex flex-col sm:flex-row gap-2">

                        <form method="POST"
                              action="{{ route('admin.pembelian.kirim', $pembelian->id) }}"
                              class="flex-1">

                            @csrf

                            <button type="submit"
                                    class="w-full px-3 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">

                                <svg class="w-3.5 h-3.5"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 10l9-7 9 7v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10z"/>
                                </svg>

                                Kirim ke Supplier

                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route('admin.pembelian.batalkan', $pembelian->id) }}">

                            @csrf

                            <button type="submit"
                                    onclick="return confirm('Yakin batalkan PO ini?')"
                                    class="px-4 py-2.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white text-xs font-bold rounded-xl transition">

                                Batalkan

                            </button>

                        </form>

                    </div>

                @else

                    <div class="mt-auto pt-6">

                        <div class="px-3 py-2.5 rounded-xl bg-white/5 border border-white/10 text-[10px] text-slate-400">
                            PO ini tidak memiliki aksi yang tersedia pada status saat ini.
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- ============================================== --}}
    {{-- ORDER ITEMS --}}
    {{-- ============================================== --}}

    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

        <div class="px-5 py-4 border-b border-slate-100">

            <div class="flex items-center gap-3">

                <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">

                    <svg class="w-4 h-4 text-[#0B5CFF]"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"/>
                    </svg>

                </div>

                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">
                        Rincian Barang Dipesan
                    </h3>

                    <p class="text-[10px] text-slate-500">
                        Detail produk dan progres penerimaan barang.
                    </p>
                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-xs text-left min-w-[850px]">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">

                    <tr>

                        <th class="px-5 py-3.5">
                            Produk / Varian
                        </th>

                        <th class="px-5 py-3.5">
                            SKU
                        </th>

                        <th class="px-5 py-3.5 text-center">
                            Dipesan
                        </th>

                        <th class="px-5 py-3.5 text-center">
                            Diterima
                        </th>

                        <th class="px-5 py-3.5 text-center">
                            Sisa
                        </th>

                        <th class="px-5 py-3.5 text-right">
                            Harga Beli
                        </th>

                        <th class="px-5 py-3.5 text-right">
                            Subtotal
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @foreach($pembelian->items as $item)

                        <tr class="hover:bg-slate-50/70 transition">

                            <td class="px-5 py-4">

                                <div class="font-bold text-slate-900">
                                    {{ $item->productVariant->product->name ?? '-' }}
                                </div>

                                <div class="mt-1 text-[11px] text-slate-500">

                                    {{ $item->productVariant->name }}

                                    @if($item->productVariant->is_serialized)

                                        <span class="inline-flex ml-1 px-2 py-0.5 rounded-full bg-cyan-50 text-cyan-700 border border-cyan-200 text-[9px] font-bold">
                                            SERIAL TRACKED
                                        </span>

                                    @endif

                                </div>

                            </td>


                            <td class="px-5 py-4 font-mono text-[11px] text-slate-500">
                                {{ $item->productVariant->sku }}
                            </td>


                            <td class="px-5 py-4 text-center">

                                <span class="font-extrabold text-slate-900">
                                    {{ number_format($item->quantity_ordered) }}
                                </span>

                            </td>


                            <td class="px-5 py-4 text-center">

                                <span class="font-extrabold text-emerald-600">
                                    {{ number_format($item->quantity_received) }}
                                </span>

                            </td>


                            <td class="px-5 py-4 text-center">

                                @if($item->remaining_quantity > 0)

                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200 text-[10px] font-bold">
                                        {{ number_format($item->remaining_quantity) }}
                                    </span>

                                @else

                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold">
                                        Lengkap
                                    </span>

                                @endif

                            </td>


                            <td class="px-5 py-4 text-right text-slate-600">
                                {{ rupiah($item->unit_cost) }}
                            </td>


                            <td class="px-5 py-4 text-right font-extrabold text-slate-900">
                                {{ rupiah($item->subtotal) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>


                <tfoot class="bg-slate-50 border-t border-slate-200">

                    <tr>

                        <td colspan="6"
                            class="px-5 py-4 text-right text-[11px] uppercase tracking-wider font-bold text-slate-500">

                            Total Purchase Order

                        </td>

                        <td class="px-5 py-4 text-right text-sm font-black text-[#0B5CFF]">

                            {{ rupiah($pembelian->total_amount) }}

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>


    {{-- ============================================== --}}
    {{-- GOODS RECEIPT --}}
    {{-- ============================================== --}}

    @if(in_array($pembelian->status, ['sent', 'partial']))

        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

            {{-- HEADER --}}
            <div class="px-5 py-4 border-b border-slate-100">

                <div class="flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center">

                        <svg class="w-4 h-4 text-emerald-600"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"/>
                        </svg>

                    </div>

                    <div>

                        <h3 class="text-sm font-extrabold text-slate-900">
                            Penerimaan Barang Masuk
                        </h3>

                        <p class="text-[10px] text-slate-500">
                            Goods Receipt — proses barang yang diterima secara fisik di gudang.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-5">

                <div class="mb-5 px-4 py-3 rounded-xl bg-blue-50 border border-blue-100">

                    <p class="text-xs text-blue-800 leading-relaxed">

                        Masukkan jumlah barang fisik yang diterima di gudang.
                        Untuk produk <strong>Serial Tracked</strong>, masukkan satu nomor seri pada setiap baris.

                    </p>

                </div>


                <form method="POST"
                      action="{{ route('admin.pembelian.terima', $pembelian->id) }}">

                    @csrf


                    @foreach($pembelian->items as $idx => $item)

                        @if($item->remaining_quantity > 0)

                            <input type="hidden"
                                   name="items[{{ $idx }}][po_item_id]"
                                   value="{{ $item->id }}">


                            <div class="mb-4 rounded-2xl border border-slate-200 overflow-hidden">

                                {{-- ITEM HEADER --}}
                                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">

                                    <div>

                                        <div class="text-xs font-extrabold text-slate-900">

                                            {{ $item->productVariant->product->name ?? '-' }}

                                            <span class="text-slate-400 mx-1">—</span>

                                            {{ $item->productVariant->name }}

                                        </div>

                                        <div class="mt-1 text-[10px] font-mono text-slate-500">

                                            SKU: {{ $item->productVariant->sku }}

                                        </div>

                                    </div>


                                    <div class="flex items-center gap-2">

                                        @if($item->productVariant->is_serialized)

                                            <span class="px-2.5 py-1 rounded-full bg-cyan-50 text-cyan-700 border border-cyan-200 text-[9px] font-extrabold">
                                                WAJIB SERIAL
                                            </span>

                                        @endif

                                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[9px] font-extrabold">

                                            Sisa:
                                            {{ number_format($item->remaining_quantity) }}
                                            unit

                                        </span>

                                    </div>

                                </div>


                                {{-- ITEM FORM --}}
                                <div class="p-4">

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                        {{-- QUANTITY --}}
                                        <div>

                                            <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                                                Jumlah Diterima Hari Ini
                                            </label>

                                            <input type="number"
                                                   name="items[{{ $idx }}][quantity_received]"
                                                   value="{{ old('items.'.$idx.'.quantity_received', 0) }}"
                                                   min="0"
                                                   max="{{ $item->remaining_quantity }}"
                                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-center focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition">

                                            <p class="mt-1 text-[10px] text-slate-400">
                                                Maksimal {{ number_format($item->remaining_quantity) }} unit.
                                            </p>

                                        </div>


                                        @if($item->productVariant->is_serialized)

                                            {{-- SERIAL --}}
                                            <div class="md:col-span-1">

                                                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                                                    Nomor Seri Unit
                                                </label>

                                                <textarea
                                                    name="items[{{ $idx }}][serial_numbers]"
                                                    rows="4"
                                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition resize-none"
                                                    placeholder="SN-ASUS-001&#10;SN-ASUS-002">{{ old('items.'.$idx.'.serial_numbers') }}</textarea>

                                                <p class="mt-1 text-[10px] text-slate-400">
                                                    Satu nomor seri untuk setiap baris.
                                                </p>

                                            </div>


                                            {{-- WARRANTY --}}
                                            <div>

                                                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                                                    Masa Garansi
                                                </label>

                                                <div class="relative">

                                                    <input type="number"
                                                           name="items[{{ $idx }}][warranty_months]"
                                                           value="{{ old('items.'.$idx.'.warranty_months', 24) }}"
                                                           min="0"
                                                           class="w-full px-3 py-2.5 pr-16 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-center focus:outline-none focus:border-[#0B5CFF] focus:bg-white transition">

                                                    <span class="absolute right-3 top-2.5 text-[10px] font-bold text-slate-400">
                                                        BULAN
                                                    </span>

                                                </div>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endif

                    @endforeach


                    <div class="pt-2">

                        <button type="submit"
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5">

                            <svg class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>

                            Proses Penerimaan & Tambah Stok

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>
@endsection