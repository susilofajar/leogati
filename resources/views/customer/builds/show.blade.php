@extends('layouts.app')

@section('title', 'Rincian Racikan: ' . $build->build_name . ' - LEOGATISTORE')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- BREADCRUMBS --}}
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-[#0B5CFF]">Akun Saya</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('customer.builds.index') }}" class="hover:text-[#0B5CFF]">Racikan PC</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">{{ $build->share_token }}</span>
    </nav>

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 text-slate-700">
                    {{ $build->share_token }}
                </span>
                @if($build->compatibility_status === 'compatible')
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Kompatibel Penuh
                    </span>
                @elseif($build->compatibility_status === 'warning')
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        Catatan Kompatibilitas
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        Tidak Kompatibel
                    </span>
                @endif
            </div>
            <h1 class="text-xl font-extrabold text-slate-900">{{ $build->build_name }}</h1>
            <p class="text-xs text-slate-500 mt-1">Disimpan pada {{ $build->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('pc_builder.index', ['build' => $build->share_token]) }}" 
                class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs">
                Buka di PC Builder
            </a>
        </div>
    </div>

    {{-- SUMMARY STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <span class="text-xs font-bold text-slate-400 uppercase">Total Estimasi Harga</span>
            <p class="text-xl font-extrabold text-[#0B5CFF] mt-1">{{ rupiah($build->total_price) }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <span class="text-xs font-bold text-slate-400 uppercase">Estimasi Daya (TDP)</span>
            <p class="text-xl font-extrabold text-slate-900 mt-1">{{ $build->estimated_wattage }} Watt</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <span class="text-xs font-bold text-slate-400 uppercase">Rekomendasi PSU</span>
            <p class="text-xl font-extrabold text-emerald-600 mt-1">Min. {{ ceil(($build->estimated_wattage * 1.3) / 50) * 50 }} Watt</p>
        </div>
    </div>

    {{-- COMPATIBILITY MESSAGES --}}
    @if(!empty($build->compatibility_messages) && is_array($build->compatibility_messages))
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-2">
            <h3 class="text-xs font-bold text-slate-700 uppercase">Status & Analisis Kompatibilitas</h3>
            <div class="space-y-1.5 text-xs">
                @foreach($build->compatibility_messages as $msg)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2 text-slate-700">
                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ is_array($msg) ? json_encode($msg) : $msg }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- COMPONENTS TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h3 class="text-xs font-bold text-slate-700 uppercase">Rincian Komponen Terpilih</h3>
        </div>
        <div class="divide-y divide-slate-100 text-xs">
            @if(is_array($build->components))
                @foreach($build->components as $slot => $comp)
                    <div class="p-4 flex items-center justify-between gap-4">
                        <div class="w-1/4">
                            <span class="font-bold text-slate-400 uppercase text-[10px] block">{{ ucfirst($slot) }}</span>
                            <span class="font-bold text-slate-900">{{ $comp['name'] ?? '-' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-extrabold text-[#0B5CFF]">{{ rupiah($comp['price'] ?? 0) }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

</div>
@endsection
