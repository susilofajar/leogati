@extends('layouts.app')

@section('title', 'Racikan PC Tersimpan - LEOGATISTORE')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- BREADCRUMBS --}}
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-[#0B5CFF]">Akun Saya</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Racikan PC Tersimpan</span>
    </nav>

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Racikan PC Tersimpan
            </h1>
            <p class="text-xs text-slate-500 mt-1">Daftar simulasi dan konfigurasi rakitan PC impian yang telah Anda simpan.</p>
        </div>
        <a href="{{ route('pc_builder.index') }}" 
            class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Racikan Baru
        </a>
    </div>

    @if($builds->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($builds as $build)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-[#0B5CFF] hover:shadow-md transition flex flex-col justify-between p-5 space-y-4">
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 text-slate-700">
                                {{ $build->share_token }}
                            </span>
                            @if($build->compatibility_status === 'compatible')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Kompatibel
                                </span>
                            @elseif($build->compatibility_status === 'warning')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Peringatan
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Inkompatibel
                                </span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900">{{ $build->build_name }}</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Disimpan pada {{ $build->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-xl text-xs">
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block">Total Biaya</span>
                                <span class="font-extrabold text-[#0B5CFF]">{{ rupiah($build->total_price) }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block">Estimasi Daya</span>
                                <span class="font-bold text-slate-700">{{ $build->estimated_wattage }} Watt</span>
                            </div>
                        </div>

                        <div class="text-xs text-slate-500">
                            <strong>{{ is_array($build->components) ? count($build->components) : 0 }}</strong> komponen terpilih
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <form action="{{ route('customer.builds.destroy', $build->share_token) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus racikan PC ini?')" class="text-rose-600 hover:underline font-bold">
                                Hapus
                            </button>
                        </form>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('customer.builds.show', $build->share_token) }}" 
                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition">
                                Rincian
                            </a>
                            <a href="{{ route('pc_builder.index', ['build' => $build->share_token]) }}" 
                                class="px-3 py-1.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-bold rounded-lg transition shadow-xs">
                                Buka di Simulator
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $builds->links() }}
        </div>
    @else
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-4 max-w-md mx-auto">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Belum Ada Racikan PC Tersimpan</h3>
                <p class="text-xs text-slate-500 mt-1">Gunakan simulator PC Builder kami untuk meracik komputer sesuai spesifikasi dan anggaran Anda.</p>
            </div>
            <a href="{{ route('pc_builder.index') }}" class="px-5 py-2.5 bg-[#0B5CFF] text-white text-xs font-bold rounded-xl shadow-xs inline-block hover:bg-[#063B9E] transition">
                Mulai Racik PC
            </a>
        </div>
    @endif

</div>
@endsection
