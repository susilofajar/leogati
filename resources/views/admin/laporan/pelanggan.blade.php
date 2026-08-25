@extends('layouts.admin')

@section('header_title', 'Laporan Pelanggan')

@section('content')
<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">Laporan Pelanggan</h2>
        <p class="text-xs text-slate-500 mt-1">Analisis pertumbuhan pelanggan baru dan pelanggan dengan nilai belanja tertinggi.</p>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
        <div class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-bold text-slate-600 mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $from->toDateString() }}"
                    class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-bold text-slate-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $to->toDateString() }}"
                    class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
            </div>
            <button type="submit" class="px-6 py-2 bg-[#0B5CFF] text-white rounded-xl text-sm font-bold hover:bg-[#063B9E] transition shrink-0">
                Tampilkan
            </button>
            <a href="{{ route('admin.laporan.pelanggan') }}" class="px-5 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition shrink-0">
                Reset
            </a>
        </div>
    </form>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan Baru</p>
            <p class="text-3xl font-black text-[#0B5CFF] mt-2">{{ number_format($newCustomers->count()) }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Registrasi {{ tgl_indo($from) }} — {{ tgl_indo($to) }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Belanja (Pelanggan Baru)</p>
            <p class="text-3xl font-black text-slate-900 mt-2">{{ rupiah($newCustomers->sum('total_pesanan')) }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Dari {{ $newCustomers->sum('total_pesanan') }} pesanan berhasil</p>
        </div>
    </div>

    {{-- TOP 10 PELANGGAN --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">TOP 10 Pelanggan Berdasarkan Total Belanja</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pelanggan dengan nilai belanja kumulatif tertinggi sepanjang waktu</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-2.5 px-4 text-left">#</th>
                        <th class="py-2.5 px-4 text-left">Pelanggan</th>
                        <th class="py-2.5 px-4 text-left">Email</th>
                        <th class="py-2.5 px-4 text-right">Pesanan</th>
                        <th class="py-2.5 px-4 text-right">Total Belanja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($topCustomers as $i => $customer)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-black text-slate-400">
                                @if($i < 3)
                                    <span class="inline-flex w-5 h-5 rounded-full items-center justify-center font-black text-[10px]
                                        {{ $i === 0 ? 'bg-amber-100 text-amber-700' : ($i === 1 ? 'bg-slate-200 text-slate-700' : 'bg-orange-100 text-orange-700') }}">
                                        {{ $i + 1 }}
                                    </span>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-black">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <span class="font-bold text-slate-900">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-slate-500">{{ $customer->email }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">{{ number_format($customer->total_pesanan) }}</td>
                            <td class="py-3 px-4 text-right font-black text-[#0B5CFF]">{{ rupiah($customer->total_belanja ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-8 text-slate-400">Belum ada data pembelian pelanggan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PELANGGAN BARU PERIODE INI --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Pelanggan Baru dalam Periode ini</h3>
            <p class="text-xs text-slate-500 mt-0.5">{{ $newCustomers->count() }} pelanggan mendaftar antara {{ tgl_indo($from) }} — {{ tgl_indo($to) }}</p>
        </div>
        @if($newCustomers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-4 text-left">Nama</th>
                            <th class="py-2.5 px-4 text-left">Email</th>
                            <th class="py-2.5 px-4 text-left">Tgl. Daftar</th>
                            <th class="py-2.5 px-4 text-right">Jumlah Pesanan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($newCustomers as $customer)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 font-bold text-slate-900">{{ $customer->name }}</td>
                                <td class="py-3 px-4 text-slate-500">{{ $customer->email }}</td>
                                <td class="py-3 px-4 text-slate-500">{{ tgl_indo($customer->created_at) }}</td>
                                <td class="py-3 px-4 text-right">
                                    @if($customer->total_pesanan > 0)
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                            {{ $customer->total_pesanan }} pesanan
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[10px]">Belum ada</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center py-8 text-xs text-slate-400">Tidak ada pelanggan baru pada periode yang dipilih.</p>
        @endif
    </div>

</div>
@endsection
