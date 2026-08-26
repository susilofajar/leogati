@extends('layouts.admin')

@section('header_title', 'Detail Stok — ' . $gudang->name)

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

        <a href="{{ route('admin.gudang.index') }}"
           class="w-10 h-10 shrink-0 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 transition flex items-center justify-center">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div>
            <h2 class="text-xl font-extrabold text-slate-900">
                {{ $gudang->name }}
            </h2>

            <div class="flex flex-wrap items-center gap-2 mt-1">
                <span class="text-xs font-mono font-semibold text-slate-500">
                    {{ $gudang->code }}
                </span>

                @if($gudang->city)
                    <span class="text-slate-300">•</span>
                    <span class="text-xs text-slate-500">
                        {{ $gudang->city }}
                    </span>
                @endif
            </div>
        </div>

    </div>


    {{-- INFO GUDANG --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- TOTAL ITEM --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0B5CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"/>
                    </svg>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Total SKU
                    </p>
                    <p class="text-xl font-extrabold text-slate-900">
                        {{ number_format($inventoryItems->total()) }}
                    </p>
                </div>

            </div>
        </div>


        {{-- KODE GUDANG --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2M9 17H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4"/>
                    </svg>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Kode Gudang
                    </p>
                    <p class="text-sm font-extrabold text-slate-900 font-mono">
                        {{ $gudang->code }}
                    </p>
                </div>

            </div>
        </div>


        {{-- LOKASI --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Lokasi
                    </p>
                    <p class="text-sm font-bold text-slate-900">
                        {{ $gudang->city ?? '-' }}
                    </p>
                </div>

            </div>
        </div>

    </div>


    {{-- STOCK TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>
                <h3 class="text-sm font-extrabold text-slate-900">
                    Daftar Stok Produk
                </h3>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    Daftar persediaan produk yang tersimpan di gudang ini.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                    {{ number_format($inventoryItems->total()) }} SKU
                </span>
            </div>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-xs text-left">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-5 py-3.5 min-w-[280px]">
                            Produk / SKU
                        </th>

                        <th class="px-5 py-3.5 text-center whitespace-nowrap">
                            Stok
                        </th>

                        <th class="px-5 py-3.5 text-center whitespace-nowrap">
                            Direservasi
                        </th>

                        <th class="px-5 py-3.5 text-center whitespace-nowrap">
                            Tersedia
                        </th>

                        <th class="px-5 py-3.5 text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($inventoryItems as $item)

                        <tr class="hover:bg-slate-50/80 transition">

                            {{-- PRODUCT --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-9 h-9 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-[#0B5CFF] flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">

                                        <p class="font-bold text-slate-900 truncate">
                                            {{ $item->productVariant->product->name ?? '-' }}
                                        </p>

                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $item->productVariant->name }}
                                        </p>

                                        <p class="text-[10px] font-mono text-slate-400 mt-0.5">
                                            SKU: {{ $item->productVariant->sku }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- STOCK --}}
                            <td class="px-5 py-4 text-center">

                                <span class="font-extrabold text-slate-900">
                                    {{ number_format($item->quantity) }}
                                </span>

                                <span class="text-[10px] text-slate-400 ml-0.5">
                                    unit
                                </span>

                            </td>


                            {{-- RESERVED --}}
                            <td class="px-5 py-4 text-center">

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ number_format($item->reserved_quantity) }}
                                </span>

                            </td>


                            {{-- AVAILABLE --}}
                            <td class="px-5 py-4 text-center">

                                @if($item->available_quantity > 0)

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ number_format($item->available_quantity) }} tersedia
                                    </span>

                                @else

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Stok Habis
                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}
                            <td class="px-5 py-4 text-right">

                                <a href="{{ route('admin.inventaris.mutasi', $item->productVariant->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-[#0B5CFF] text-slate-700 hover:text-white font-bold rounded-lg transition">

                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>

                                    Mutasi

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">

                                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"/>
                                    </svg>
                                </div>

                                <p class="text-sm font-bold text-slate-700">
                                    Belum Ada Stok
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    Belum ada stok produk yang tercatat di gudang ini.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($inventoryItems->hasPages())

            <div class="px-5 py-4 border-t border-slate-100">
                {{ $inventoryItems->links() }}
            </div>

        @endif

    </div>

</div>
@endsection