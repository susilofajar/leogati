@extends('layouts.admin')

@section('title', 'Manajemen Inventaris')

@section('content')

<div class="space-y-6">
{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>

            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">
                    Manajemen Inventaris
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Kelola stok dan mutasi semua varian produk.
                </p>
            </div>
        </div>
    </div>
</div>


{{-- FILTER --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
    <div class="p-5">

        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414A1 1 0 0013 14.414V19l-2 2v-6.586a1 1 0 00-.293-.707L4.293 7.293A1 1 0 014 6.586V4z"/>
                </svg>
            </div>

            <div>
                <h2 class="text-sm font-bold text-slate-900">Filter Inventaris</h2>
                <p class="text-[11px] text-slate-500">
                    Gunakan filter untuk menemukan stok tertentu.
                </p>
            </div>
        </div>

        <form method="GET"
              action="{{ route('admin.inventaris.index') }}"
              class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

            {{-- SEARCH --}}
            <div class="md:col-span-6">
                <label for="cari"
                       class="block text-xs font-bold text-slate-600 mb-1.5">
                    Cari Produk / SKU
                </label>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                        </svg>
                    </div>

                    <input
                        type="text"
                        name="cari"
                        id="cari"
                        value="{{ request('cari') }}"
                        placeholder="Nama produk atau SKU..."
                        class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#0B5CFF] transition"
                    >
                </div>
            </div>


            {{-- LOW STOCK --}}
            <div class="md:col-span-4">
                <label class="flex items-center gap-3 min-h-[42px] px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer transition">

                    <input
                        type="checkbox"
                        name="stok_rendah"
                        id="stok_rendah"
                        value="1"
                        {{ request('stok_rendah') ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-300 text-[#0B5CFF] focus:ring-[#0B5CFF]"
                    >

                    <span class="text-xs font-semibold text-slate-600">
                        Tampilkan stok rendah saja
                        <span class="text-slate-400 font-normal">(&lt; 5)</span>
                    </span>

                </label>
            </div>


            {{-- FILTER BUTTON --}}
            <div class="md:col-span-2">
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0B5CFF] hover:bg-[#0849CC] text-white text-xs font-bold shadow-sm hover:shadow transition">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>

                    Filter
                </button>
            </div>

        </form>
    </div>
</div>


{{-- INVENTORY TABLE --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

    {{-- TABLE HEADER --}}
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h2 class="text-sm font-bold text-slate-900">
                Daftar Inventaris
            </h2>
            <p class="text-[11px] text-slate-500 mt-0.5">
                Stok semua varian produk yang tersedia.
            </p>
        </div>

        @if(isset($variants))
            <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                {{ number_format($variants->total()) }} data
            </span>
        @endif
    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[900px] text-sm">

            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">

                    <th class="px-5 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Produk / Varian
                    </th>

                    <th class="px-5 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        SKU
                    </th>

                    <th class="px-5 py-3 text-center text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Stok
                    </th>

                    <th class="px-5 py-3 text-center text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Serialized
                    </th>

                    <th class="px-5 py-3 text-right text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Harga Jual
                    </th>

                    <th class="px-5 py-3 text-right text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Aksi
                    </th>

                </tr>
            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($variants as $variant)

                    <tr class="hover:bg-slate-50/80 transition">

                        {{-- PRODUCT --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3 min-w-0">

                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <div class="font-bold text-xs text-slate-900 truncate">
                                        {{ $variant->product->name ?? '-' }}
                                    </div>

                                    <div class="text-[11px] text-slate-500 truncate mt-0.5">
                                        {{ $variant->name }}
                                    </div>
                                </div>

                            </div>
                        </td>


                        {{-- SKU --}}
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg bg-slate-100 text-slate-600 font-mono text-[10px] font-semibold">
                                {{ $variant->sku }}
                            </span>
                        </td>


                        {{-- STOCK --}}
                        <td class="px-5 py-4 text-center">

                            @if($variant->stock <= 0)

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 text-[10px] font-black">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Habis
                                </span>

                            @elseif($variant->stock < 5)

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    {{ number_format($variant->stock) }} unit
                                </span>

                            @else

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ number_format($variant->stock) }} unit
                                </span>

                            @endif

                        </td>


                        {{-- SERIALIZED --}}
                        <td class="px-5 py-4 text-center">

                            @if($variant->is_serialized)

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-sky-100 text-sky-700 text-[10px] font-bold">
                                    Serial
                                </span>

                            @else

                                <span class="text-slate-400 text-xs">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- PRICE --}}
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <span class="text-xs font-black text-slate-900">
                                {{ rupiah($variant->price) }}
                            </span>
                        </td>


                        {{-- ACTION --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">

                                <a
                                    href="{{ route('admin.inventaris.mutasi', $variant->id) }}"
                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 text-[10px] font-bold transition whitespace-nowrap">

                                    <svg class="w-3.5 h-3.5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M4 4v5h5M20 20v-5h-5M5.5 9A7 7 0 0117 5.5L20 9M18.5 15A7 7 0 017 18.5L4 15"/>
                                    </svg>

                                    Mutasi
                                </a>


                                <a
                                    href="{{ route('admin.inventaris.adjust_form', $variant->id) }}"
                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 text-[10px] font-bold transition whitespace-nowrap">

                                    <svg class="w-3.5 h-3.5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7m-5.5-8.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 7.5-7.5z"/>
                                    </svg>

                                    Sesuaikan
                                </a>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">

                            <div class="flex flex-col items-center justify-center">

                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>

                                <p class="text-sm font-bold text-slate-700">
                                    Tidak ada data inventaris
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    Belum ada varian produk yang sesuai dengan filter.
                                </p>

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    @if($variants->hasPages())

        <div class="px-5 py-4 border-t border-slate-100 bg-white">
            {{ $variants->links() }}
        </div>

    @endif

</div>

</div>
@endsection
