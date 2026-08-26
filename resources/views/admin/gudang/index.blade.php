@extends('layouts.admin')

@section('header_title', 'Manajemen Gudang')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Manajemen Gudang</h2>
            <p class="text-xs text-slate-500 mt-1">
                Kelola lokasi penyimpanan stok produk LEOGATISTORE.
            </p>
        </div>
    </div>

    {{-- DAFTAR GUDANG --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($warehouses as $warehouse)

            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden hover:border-slate-300 hover:shadow-sm transition">

                {{-- CARD HEADER --}}
                <div class="p-5">

                    <div class="flex items-start justify-between gap-3 mb-4">

                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-1.5 mb-2">

                                {{-- CODE --}}
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $warehouse->code }}
                                </span>

                                {{-- DEFAULT --}}
                                @if($warehouse->is_default)
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-[#0B5CFF] border border-blue-100">
                                        Utama
                                    </span>
                                @endif

                            </div>

                            <h3 class="text-sm font-extrabold text-slate-900 truncate">
                                {{ $warehouse->name }}
                            </h3>

                        </div>

                        {{-- STATUS --}}
                        @if($warehouse->is_active)
                            <span class="shrink-0 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        @else
                            <span class="shrink-0 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                Nonaktif
                            </span>
                        @endif

                    </div>

                    {{-- INFORMATION --}}
                    <div class="space-y-2.5">

                        {{-- ADDRESS --}}
                        @if($warehouse->address)
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 shrink-0 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                        Lokasi
                                    </p>
                                    <p class="text-xs text-slate-600 leading-relaxed">
                                        {{ $warehouse->address }}
                                        @if($warehouse->city), {{ $warehouse->city }}@endif
                                        @if($warehouse->province), {{ $warehouse->province }}@endif
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- PIC --}}
                        @if($warehouse->pic_name)
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 shrink-0 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                        PIC
                                    </p>
                                    <p class="text-xs font-semibold text-slate-700">
                                        {{ $warehouse->pic_name }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- PHONE --}}
                        @if($warehouse->phone)
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 shrink-0 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 5a2 2 0 012-2h3.28a2 2 0 011.94 1.515l.6 2.4a2 2 0 01-.45 1.84L8.76 10.37a16.016 16.016 0 006.87 6.87l1.615-1.615a2 2 0 011.84-.45l2.4.6A2 2 0 0123 17.72V21a2 2 0 01-2 2h-1C10.163 23 1 13.837 1 3V2a2 2 0 012-2z"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                        Telepon
                                    </p>
                                    <p class="text-xs font-semibold text-slate-700">
                                        {{ $warehouse->phone }}
                                    </p>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-5 py-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between gap-4">

                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-extrabold text-[#0B5CFF]">
                                {{ number_format($warehouse->total_skus ?? 0) }}
                            </span>
                            <span class="text-[10px] font-semibold text-slate-400">
                                SKU
                            </span>
                        </div>

                        <p class="text-[10px] text-slate-500">
                            Total SKU
                        </p>
                    </div>

                    <a href="{{ route('admin.gudang.show', $warehouse) }}"
                       class="px-3.5 py-2 bg-white hover:bg-[#0B5CFF] text-slate-700 hover:text-white text-[11px] font-bold rounded-xl border border-slate-200 hover:border-[#0B5CFF] transition flex items-center gap-1.5">

                        Lihat Stok

                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>

                    </a>

                </div>

            </div>

        @empty

            {{-- EMPTY STATE --}}
            <div class="col-span-full">

                <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs py-12 px-5 text-center">

                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>
                        </svg>
                    </div>

                    <h3 class="text-sm font-bold text-slate-900">
                        Belum Ada Gudang
                    </h3>

                    <p class="text-xs text-slate-500 mt-1">
                        Belum ada gudang yang terdaftar dalam sistem.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</div>
@endsection