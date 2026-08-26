@extends('layouts.admin')

@section('title', 'Riwayat Mutasi Stok — ' . $varian->sku)

@section('content')

<div class="space-y-6">

{{-- HEADER --}}
<div class="flex flex-col lg:flex-row lg:items-center gap-4">

    <div class="flex items-center gap-3 min-w-0 flex-1">

        {{-- BACK BUTTON --}}
        <a
            href="{{ route('admin.inventaris.index') }}"
            class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 flex items-center justify-center shrink-0 transition"
            title="Kembali ke inventaris"
        >
            <svg class="w-5 h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        {{-- TITLE --}}
        <div class="min-w-0">
            <div class="flex items-center gap-2 mb-0.5">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0B5CFF] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 4v5h5M20 20v-5h-5M5.5 9A7 7 0 0117 5.5L20 9M18.5 15A7 7 0 017 18.5L4 15"/>
                    </svg>
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-slate-900 truncate">
                    Riwayat Mutasi Stok
                </h1>
            </div>

            <p class="text-xs sm:text-sm text-slate-500 truncate">
                {{ $varian->product->name ?? '' }}
                <span class="text-slate-300 mx-1">—</span>
                {{ $varian->name }}
                <span class="text-slate-300 mx-1">•</span>
                <span class="font-mono text-slate-600">{{ $varian->sku }}</span>
            </p>
        </div>

    </div>


    {{-- ACTION --}}
    <div class="shrink-0">

        <a
            href="{{ route('admin.inventaris.adjust_form', $varian->id) }}"
            class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-black text-xs font-bold shadow-sm hover:shadow transition"
        >
            <svg class="w-4 h-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7m5.5-8.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 7.5-7.5z"/>
            </svg>

            Sesuaikan Stok
        </a>

    </div>

</div>


{{-- KPI / STOCK SUMMARY --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

    {{-- STOK SAAT INI --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
        <div class="flex items-center justify-between">

            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                Stok Saat Ini
            </p>

            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center">
                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>

        </div>

        <p class="text-3xl font-black text-slate-900 mt-2">
            {{ number_format($varian->stock) }}
        </p>

        <p class="text-[11px] text-slate-500 mt-1">
            Stok saat ini pada cache inventaris
        </p>
    </div>


    {{-- TOTAL MASUK --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
        <div class="flex items-center justify-between">

            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                Total Masuk
            </p>

            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m0 0l-6-6m6 6l6-6"/>
                </svg>
            </div>

        </div>

        <p class="text-3xl font-black text-emerald-600 mt-2">
            {{ number_format($movements->where('quantity_change', '>', 0)->sum('quantity_change')) }}
        </p>

        <p class="text-[11px] text-slate-500 mt-1">
            Total stok masuk pada halaman ini
        </p>
    </div>


    {{-- TOTAL KELUAR --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
        <div class="flex items-center justify-between">

            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                Total Keluar
            </p>

            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 20V4m0 0l6 6m-6-6l-6 6"/>
                </svg>
            </div>

        </div>

        <p class="text-3xl font-black text-rose-600 mt-2">
            {{ number_format(abs($movements->where('quantity_change', '<', 0)->sum('quantity_change'))) }}
        </p>

        <p class="text-[11px] text-slate-500 mt-1">
            Total stok keluar pada halaman ini
        </p>
    </div>


    {{-- TOTAL TRANSAKSI --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
        <div class="flex items-center justify-between">

            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                Total Transaksi
            </p>

            <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>

        </div>

        <p class="text-3xl font-black text-slate-900 mt-2">
            {{ number_format($movements->total()) }}
        </p>

        <p class="text-[11px] text-slate-500 mt-1">
            Total seluruh transaksi mutasi
        </p>
    </div>

</div>


{{-- MOVEMENT TABLE --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

    {{-- TABLE HEADER --}}
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

        <div>
            <h2 class="text-sm font-bold text-slate-900">
                Riwayat Mutasi
            </h2>

            <p class="text-[11px] text-slate-500 mt-0.5">
                Catatan seluruh perubahan stok varian ini.
            </p>
        </div>

        <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
            {{ number_format($movements->total()) }} transaksi
        </span>

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[1100px] text-sm">

            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">

                    <th class="px-5 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Tanggal
                    </th>

                    <th class="px-5 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Jenis
                    </th>

                    <th class="px-5 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Gudang
                    </th>

                    <th class="px-5 py-3 text-center text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Perubahan
                    </th>

                    <th class="px-5 py-3 text-center text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Sebelum
                    </th>

                    <th class="px-5 py-3 text-center text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Sesudah
                    </th>

                    <th class="px-5 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Catatan
                    </th>

                    <th class="px-5 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        Dilakukan Oleh
                    </th>

                </tr>
            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($movements as $movement)

                    <tr class="hover:bg-slate-50/80 transition">

                        {{-- DATE --}}
                        <td class="px-5 py-4 whitespace-nowrap">

                            <div class="flex items-center gap-2">

                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
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

                                <span class="text-[11px] text-slate-600">
                                    {{ tgl_indo($movement->created_at) }}
                                </span>

                            </div>

                        </td>


                        {{-- TYPE --}}
                        <td class="px-5 py-4">

                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold">
                                {{ $movement->type_label }}
                            </span>

                        </td>


                        {{-- WAREHOUSE --}}
                        <td class="px-5 py-4">

                            <div class="flex items-center gap-2">

                                <svg class="w-4 h-4 text-slate-400 shrink-0"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 21h18M5 21V10l7-5 7 5v11M9 21v-6h6v6"/>
                                </svg>

                                <span class="text-xs text-slate-600">
                                    {{ $movement->warehouse->name ?? '-' }}
                                </span>

                            </div>

                        </td>


                        {{-- QUANTITY CHANGE --}}
                        <td class="px-5 py-4 text-center">

                            @if($movement->is_positive)

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black">
                                    <span class="text-xs">+</span>
                                    {{ number_format($movement->quantity_change) }}
                                </span>

                            @else

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 text-[10px] font-black">
                                    <span class="text-xs">−</span>
                                    {{ number_format(abs($movement->quantity_change)) }}
                                </span>

                            @endif

                        </td>


                        {{-- BEFORE --}}
                        <td class="px-5 py-4 text-center">
                            <span class="text-xs font-semibold text-slate-500">
                                {{ number_format($movement->quantity_before) }}
                            </span>
                        </td>


                        {{-- AFTER --}}
                        <td class="px-5 py-4 text-center">
                            <span class="text-xs font-black text-slate-900">
                                {{ number_format($movement->quantity_after) }}
                            </span>
                        </td>


                        {{-- NOTES --}}
                        <td class="px-5 py-4">

                            <div
                                class="max-w-[220px] truncate text-xs text-slate-500"
                                title="{{ $movement->notes ?? '-' }}"
                            >
                                {{ $movement->notes ?? '-' }}
                            </div>

                        </td>


                        {{-- PERFORMER --}}
                        <td class="px-5 py-4">

                            <div class="flex items-center gap-2">

                                <div class="w-8 h-8 rounded-full bg-blue-50 text-[#0B5CFF] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>

                                <span class="text-xs font-semibold text-slate-700 whitespace-nowrap">
                                    {{ $movement->performer->name ?? 'Sistem' }}
                                </span>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="px-5 py-14 text-center">

                            <div class="flex flex-col items-center justify-center">

                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">

                                    <svg class="w-7 h-7"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M4 4v5h5M20 20v-5h-5M5.5 9A7 7 0 0117 5.5L20 9M18.5 15A7 7 0 017 18.5L4 15"/>
                                    </svg>

                                </div>

                                <p class="text-sm font-bold text-slate-700">
                                    Belum Ada Riwayat Mutasi
                                </p>

                                <p class="text-xs text-slate-400 mt-1 max-w-sm">
                                    Belum ada transaksi perubahan stok untuk varian ini.
                                </p>

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    @if($movements->hasPages())

        <div class="px-5 py-4 border-t border-slate-100 bg-white">
            {{ $movements->links() }}
        </div>

    @endif

</div>

</div>
@endsection
