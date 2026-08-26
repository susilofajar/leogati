@extends('layouts.admin')

@section('header_title', 'Detail Nomor Seri — ' . $nomor_seri->serial_number)

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center gap-4">

        <a href="{{ route('admin.nomor_seri.index') }}"
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


        <div class="flex-1 min-w-0">

            <div class="flex flex-col sm:flex-row sm:items-center gap-2">

                <h2 class="text-xl font-extrabold text-slate-900">
                    Nomor Seri:
                </h2>

                <span class="font-mono text-lg font-extrabold text-[#0B5CFF] break-all">
                    {{ $nomor_seri->serial_number }}
                </span>

            </div>

            <p class="text-xs text-slate-500 mt-1">
                {{ $nomor_seri->productVariant->product->name ?? '-' }}
                @if($nomor_seri->productVariant->name)
                    <span class="text-slate-300 mx-1">—</span>
                    {{ $nomor_seri->productVariant->name }}
                @endif
            </p>

        </div>


        {{-- STATUS --}}
        @php
            $statusClasses = [
                'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
                'danger' => 'bg-rose-50 text-rose-700 border-rose-200',
                'primary' => 'bg-blue-50 text-blue-700 border-blue-200',
                'secondary' => 'bg-slate-100 text-slate-600 border-slate-200',
            ];

            $statusClass = $statusClasses[$nomor_seri->status_color]
                ?? 'bg-slate-100 text-slate-600 border-slate-200';
        @endphp

        <span class="self-start lg:self-center inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-bold border {{ $statusClass }} whitespace-nowrap">
            Status: {{ $nomor_seri->status_label }}
        </span>

    </div>


    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- SERIAL --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-4">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#0B5CFF]"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 5l4 4m0 0l-9.5 9.5L5 19l.5-4.5L15 5z"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Nomor Seri
                    </p>

                    <p class="text-xs font-mono font-extrabold text-slate-900 truncate">
                        {{ $nomor_seri->serial_number }}
                    </p>
                </div>

            </div>

        </div>


        {{-- SKU --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-4">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-500"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        SKU
                    </p>

                    <p class="text-xs font-mono font-extrabold text-slate-900 truncate">
                        {{ $nomor_seri->productVariant->sku ?? '-' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- GUDANG --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-4">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Lokasi Gudang
                    </p>

                    <p class="text-xs font-bold text-slate-900 truncate">
                        {{ $nomor_seri->warehouse->name ?? 'Gudang Pusat' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- WARRANTY --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-4">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-xl {{ $nomor_seri->warranty_expires_at && $nomor_seri->isUnderWarranty() ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100' }} border flex items-center justify-center">

                    <svg class="w-4 h-4 {{ $nomor_seri->warranty_expires_at && $nomor_seri->isUnderWarranty() ? 'text-emerald-600' : 'text-rose-600' }}"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.291 9 11.623C17.176 19.291 21 14.591 21 9c0-1.046-.133-2.061-.382-3.016z"/>
                    </svg>

                </div>

                <div class="min-w-0">

                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Garansi
                    </p>

                    @if($nomor_seri->warranty_expires_at)

                        @if($nomor_seri->isUnderWarranty())

                            <p class="text-xs font-bold text-emerald-700 truncate">
                                Aktif s/d {{ tgl_indo($nomor_seri->warranty_expires_at) }}
                            </p>

                        @else

                            <p class="text-xs font-bold text-rose-700 truncate">
                                Kadaluarsa
                            </p>

                        @endif

                    @else

                        <p class="text-xs font-semibold text-slate-400">
                            Tidak tercatat
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- CONTENT --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">


        {{-- PRODUCT INFORMATION --}}
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
                            Informasi Produk & Unit
                        </h3>

                        <p class="text-[10px] text-slate-500">
                            Informasi identitas unit dan lokasi penyimpanan.
                        </p>
                    </div>

                </div>

            </div>


            <div class="p-5">

                <div class="divide-y divide-slate-100">

                    {{-- PRODUCT --}}
                    <div class="py-3 first:pt-0">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4">
                            <span class="text-[11px] text-slate-400 sm:w-36 shrink-0">
                                Nama Produk
                            </span>

                            <span class="text-xs font-bold text-slate-800">
                                {{ $nomor_seri->productVariant->product->name ?? '-' }}
                            </span>
                        </div>
                    </div>


                    {{-- VARIANT --}}
                    <div class="py-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4">
                            <span class="text-[11px] text-slate-400 sm:w-36 shrink-0">
                                Varian Produk
                            </span>

                            <span class="text-xs font-semibold text-slate-700">
                                {{ $nomor_seri->productVariant->name ?? '-' }}
                            </span>
                        </div>
                    </div>


                    {{-- SKU --}}
                    <div class="py-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4">
                            <span class="text-[11px] text-slate-400 sm:w-36 shrink-0">
                                SKU
                            </span>

                            <span class="text-xs font-mono font-bold text-[#0B5CFF]">
                                {{ $nomor_seri->productVariant->sku ?? '-' }}
                            </span>
                        </div>
                    </div>


                    {{-- WAREHOUSE --}}
                    <div class="py-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4">
                            <span class="text-[11px] text-slate-400 sm:w-36 shrink-0">
                                Lokasi Gudang
                            </span>

                            <span class="text-xs font-semibold text-slate-700">
                                {{ $nomor_seri->warehouse->name ?? 'Gudang Pusat' }}
                            </span>
                        </div>
                    </div>


                    {{-- WARRANTY --}}
                    <div class="pt-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">

                            <span class="text-[11px] text-slate-400 sm:w-36 shrink-0">
                                Garansi Resmi
                            </span>

                            @if($nomor_seri->warranty_expires_at)

                                @if($nomor_seri->isUnderWarranty())

                                    <span class="inline-flex items-center gap-1.5 self-start px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.291 9 11.623C17.176 19.291 21 14.591 21 9c0-1.046-.133-2.061-.382-3.016z"/>
                                        </svg>
                                        Aktif s/d {{ tgl_indo($nomor_seri->warranty_expires_at) }}
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 self-start px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Kadaluarsa {{ tgl_indo($nomor_seri->warranty_expires_at) }}
                                    </span>

                                @endif

                            @else

                                <span class="text-xs text-slate-400">
                                    Tidak tercatat
                                </span>

                            @endif

                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- LIFECYCLE --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100">

                <div class="flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-violet-50 border border-violet-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">
                            Riwayat Siklus Hidup Unit
                        </h3>

                        <p class="text-[10px] text-slate-500">
                            Riwayat penerimaan dan penjualan unit.
                        </p>
                    </div>

                </div>

            </div>


            <div class="p-5">

                <div class="relative">

                    {{-- LINE --}}
                    <div class="absolute left-4 top-5 bottom-5 w-px bg-slate-200"></div>


                    {{-- RECEIVED --}}
                    <div class="relative flex gap-4 pb-6">

                        <div class="relative z-10 w-8 h-8 shrink-0 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center">

                            <svg class="w-4 h-4 text-emerald-600"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 4v12m0 0l-4-4m4 4l4-4M5 20h14"/>
                            </svg>

                        </div>

                        <div class="flex-1 min-w-0">

                            <h4 class="text-xs font-extrabold text-slate-900">
                                Penerimaan dari Supplier
                            </h4>

                            <p class="text-[11px] text-slate-500 mt-1">
                                Tanggal:
                                <span class="font-semibold text-slate-700">
                                    {{ $nomor_seri->purchased_at ? tgl_indo($nomor_seri->purchased_at) : '-' }}
                                </span>
                            </p>

                            @if($nomor_seri->purchaseOrder)

                                <div class="mt-2 p-3 rounded-xl bg-slate-50 border border-slate-100">

                                    <p class="text-[10px] uppercase tracking-wide font-bold text-slate-400 mb-1">
                                        Purchase Order
                                    </p>

                                    <a href="{{ route('admin.pembelian.show', $nomor_seri->purchaseOrder->id) }}"
                                       class="text-xs font-bold text-[#0B5CFF] hover:text-[#063B9E] transition">
                                        #{{ $nomor_seri->purchaseOrder->po_number }}
                                    </a>

                                    <p class="text-[11px] text-slate-500 mt-1">
                                        Supplier:
                                        <span class="font-semibold text-slate-700">
                                            {{ $nomor_seri->purchaseOrder->supplier->name ?? '-' }}
                                        </span>
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- SOLD --}}
                    <div class="relative flex gap-4">

                        <div class="relative z-10 w-8 h-8 shrink-0 rounded-full bg-blue-50 border border-blue-200 flex items-center justify-center">

                            <svg class="w-4 h-4 text-[#0B5CFF]"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 2m2-2l2 2m8-2l2 2m-2-2l-2 2M9 21h.01M18 21h.01"/>
                            </svg>

                        </div>

                        <div class="flex-1 min-w-0">

                            <h4 class="text-xs font-extrabold text-slate-900">
                                Penjualan ke Pelanggan
                            </h4>

                            <p class="text-[11px] text-slate-500 mt-1">
                                Tanggal Terjual:
                                <span class="font-semibold text-slate-700">
                                    {{ $nomor_seri->sold_at ? tgl_indo($nomor_seri->sold_at) : 'Belum terjual' }}
                                </span>
                            </p>

                            @if($nomor_seri->orderItem && $nomor_seri->orderItem->order)

                                <div class="mt-2 p-3 rounded-xl bg-slate-50 border border-slate-100">

                                    <p class="text-[10px] uppercase tracking-wide font-bold text-slate-400 mb-1">
                                        Pesanan
                                    </p>

                                    <a href="{{ route('admin.pesanan.show', $nomor_seri->orderItem->order->id) }}"
                                       class="text-xs font-bold text-[#0B5CFF] hover:text-[#063B9E] transition">
                                        #{{ $nomor_seri->orderItem->order->order_number }}
                                    </a>

                                </div>

                            @endif

                            @if($nomor_seri->customer)

                                <div class="mt-2 flex items-center gap-2">

                                    <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-slate-500"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-[10px] text-slate-400">
                                            Pembeli
                                        </p>

                                        <p class="text-[11px] font-semibold text-slate-700">
                                            {{ $nomor_seri->customer->name }}
                                            <span class="text-slate-400 font-normal">
                                                ({{ $nomor_seri->customer->email }})
                                            </span>
                                        </p>
                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection