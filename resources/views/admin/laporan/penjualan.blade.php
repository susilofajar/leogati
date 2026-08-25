@extends('layouts.admin')

@section('header_title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Laporan Penjualan</h2>
            <p class="text-xs text-slate-500 mt-1">Analisis pendapatan, produk terlaris, dan performa penjualan per kategori & merek.</p>
        </div>
    </div>

    {{-- FILTER RENTANG TANGGAL --}}
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
            <a href="{{ route('admin.laporan.penjualan') }}" class="px-5 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition shrink-0">
                Reset
            </a>
        </div>
    </form>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pendapatan</p>
            <p class="text-2xl font-black text-[#0B5CFF] mt-2">{{ rupiah($totalRevenue) }}</p>
            <p class="text-[11px] text-slate-500 mt-1">{{ tgl_indo($from) }} — {{ tgl_indo($to) }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pesanan</p>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ number_format($salesSummary->sum('total_pesanan')) }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Pesanan berhasil bayar</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata / Hari</p>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ rupiah($salesSummary->count() > 0 ? $totalRevenue / $salesSummary->count() : 0) }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Dari {{ $salesSummary->count() }} hari aktif</p>
        </div>
    </div>

    {{-- TREND BULANAN TAHUN INI --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
        <h3 class="text-sm font-bold text-slate-900 mb-4">Tren Penjualan Bulanan {{ now()->year }}</h3>
        @php
            $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            $monthlyMap = $monthlySales->pluck('total_pendapatan', 'bulan');
            $maxMonthly = $monthlyMap->max() ?: 1;
        @endphp
        <div class="flex items-end justify-between gap-1.5 h-24">
            @for($m = 1; $m <= 12; $m++)
                @php $val = $monthlyMap->get($m, 0); $barH = $maxMonthly > 0 ? ($val / $maxMonthly) * 100 : 0; @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full rounded-t"
                         style="height: {{ max($barH, $val > 0 ? 6 : 2) }}%;
                                background: {{ $val > 0 ? 'linear-gradient(180deg, #0B5CFF, #063B9E)' : '#e2e8f0' }}">
                    </div>
                    <span class="text-[9px] text-slate-500">{{ $monthNames[$m - 1] }}</span>
                </div>
            @endfor
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- TOP 10 PRODUK TERLARIS --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">TOP 10 Produk Terlaris</h3>
                <p class="text-xs text-slate-500 mt-0.5">Berdasarkan kuantitas terjual dalam periode ini</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-4 text-left">#</th>
                            <th class="py-2.5 px-4 text-left">Produk / SKU</th>
                            <th class="py-2.5 px-4 text-right">Qty</th>
                            <th class="py-2.5 px-4 text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topProducts as $i => $item)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 text-slate-400 font-bold">{{ $i + 1 }}</td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-900 truncate max-w-[160px]">{{ $item->product_name }}</p>
                                    <p class="text-slate-500 text-[10px]">{{ $item->variant_name }} &bull; {{ $item->sku }}</p>
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-slate-900">{{ number_format($item->total_qty) }}</td>
                                <td class="py-3 px-4 text-right font-bold text-[#0B5CFF]">{{ rupiah($item->total_revenue) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-8 text-slate-400">Belum ada data penjualan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PENJUALAN PER KATEGORI --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Penjualan per Kategori</h3>
                <p class="text-xs text-slate-500 mt-0.5">Distribusi pendapatan berdasarkan kategori produk</p>
            </div>
            @php $totalCat = $salesByCategory->sum('total_revenue') ?: 1; @endphp
            <div class="p-5 space-y-3">
                @forelse($salesByCategory as $cat)
                    @php $pct = round(($cat->total_revenue / $totalCat) * 100, 1); @endphp
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-bold text-slate-700">{{ $cat->kategori }}</span>
                            <span class="text-slate-500">{{ rupiah($cat->total_revenue) }} <span class="text-slate-400">({{ $pct }}%)</span></span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-[#0B5CFF] to-[#063B9E]" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center py-4 text-xs text-slate-400">Belum ada data.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- PENJUALAN PER MEREK --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Penjualan per Merek</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-2.5 px-4 text-left">Merek</th>
                        <th class="py-2.5 px-4 text-right">Qty Terjual</th>
                        <th class="py-2.5 px-4 text-right">Total Revenue</th>
                        <th class="py-2.5 px-4 text-right">Proporsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $totalBrand = $salesByBrand->sum('total_revenue') ?: 1; @endphp
                    @forelse($salesByBrand as $brand)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $brand->merek }}</td>
                            <td class="py-3 px-4 text-right text-slate-700">{{ number_format($brand->total_qty) }}</td>
                            <td class="py-3 px-4 text-right font-bold text-[#0B5CFF]">{{ rupiah($brand->total_revenue) }}</td>
                            <td class="py-3 px-4 text-right text-slate-500">{{ round(($brand->total_revenue / $totalBrand) * 100, 1) }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-8 text-slate-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DATA HARIAN --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Rincian Penjualan Harian</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-2.5 px-4 text-left">Tanggal</th>
                        <th class="py-2.5 px-4 text-right">Jumlah Pesanan</th>
                        <th class="py-2.5 px-4 text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($salesSummary as $day)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ tgl_indo(\Carbon\Carbon::parse($day->tanggal)) }}</td>
                            <td class="py-3 px-4 text-right text-slate-700">{{ number_format($day->total_pesanan) }}</td>
                            <td class="py-3 px-4 text-right font-bold text-[#0B5CFF]">{{ rupiah($day->total_pendapatan) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-8 text-slate-400">Tidak ada data penjualan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
                @if($salesSummary->count() > 0)
                    <tfoot class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <td class="py-3 px-4 text-xs font-black text-slate-700">TOTAL</td>
                            <td class="py-3 px-4 text-right text-xs font-black text-slate-900">{{ number_format($salesSummary->sum('total_pesanan')) }}</td>
                            <td class="py-3 px-4 text-right text-xs font-black text-[#0B5CFF]">{{ rupiah($totalRevenue) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection
