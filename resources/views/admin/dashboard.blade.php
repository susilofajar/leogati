@extends('layouts.admin')

@section('header_title', 'Dashboard Operasional')

@section('content')
<div class="space-y-6">

    {{-- WELCOME BANNER --}}
    <div class="bg-gradient-to-r from-[#071A3D] to-[#0B5CFF] rounded-2xl p-6 text-white flex flex-col sm:flex-row justify-between items-center gap-4 shadow-sm">
        <div>
            <h2 class="text-xl font-extrabold">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="text-xs text-blue-100 mt-1">Masuk sebagai <strong class="text-white">{{ auth()->user()->role_display_name }}</strong> &mdash; {{ now()->translatedFormat('l, d F Y \P\u\k\u\l H:i') }} WIB</p>
        </div>
        <div class="flex items-center space-x-3 shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                Sistem Aktif
            </span>
        </div>
    </div>

    {{-- KPI METRICS GRID (8 CARDS) --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Revenue Bulan Ini --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs col-span-2 lg:col-span-2">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900 mt-2">{{ rupiah($metrics['revenue_bulan_ini']) }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Total pendapatan periode {{ now()->translatedFormat('F Y') }}</p>
        </div>

        {{-- Pesanan Hari Ini --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pesanan Hari Ini</p>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $metrics['pesanan_hari_ini'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Pesanan masuk hari ini</p>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Perlu Diproses</p>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $metrics['pending_orders'] }}</p>
            <a href="{{ route('admin.pesanan.index') }}" class="text-[11px] text-[#0B5CFF] hover:underline mt-1 block">Lihat pesanan →</a>
        </div>

        {{-- Stok Rendah --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Rendah</p>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $metrics['stok_rendah'] }}</p>
            <a href="{{ route('admin.laporan.inventaris') }}" class="text-[11px] text-rose-600 hover:underline mt-1 block">Cek stok kritis →</a>
        </div>

        {{-- Klaim Garansi Aktif --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Klaim Garansi Aktif</p>
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $metrics['klaim_aktif'] }}</p>
            <a href="{{ route('admin.garansi.index') }}" class="text-[11px] text-[#0B5CFF] hover:underline mt-1 block">Proses klaim →</a>
        </div>

        {{-- Ulasan Menunggu --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ulasan Menunggu</p>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $metrics['ulasan_menunggu'] }}</p>
            <a href="{{ route('admin.ulasan.index') }}" class="text-[11px] text-[#0B5CFF] hover:underline mt-1 block">Moderasi ulasan →</a>
        </div>

        {{-- Total Pelanggan --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pelanggan</p>
                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ number_format($metrics['total_pelanggan']) }}</p>
            <a href="{{ route('admin.laporan.pelanggan') }}" class="text-[11px] text-[#0B5CFF] hover:underline mt-1 block">Analitik pelanggan →</a>
        </div>

    </div>

    {{-- TREND PENJUALAN 7 HARI --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Tren Pendapatan 7 Hari Terakhir</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pendapatan harian dari pesanan yang berhasil dibayar</p>
            </div>
            <a href="{{ route('admin.laporan.penjualan') }}" class="text-xs font-bold text-[#0B5CFF] hover:underline">Laporan Lengkap →</a>
        </div>
        <div class="flex items-end justify-between gap-2 h-32">
            @foreach($salesTrend as $day)
                @php $barHeight = $maxRevenue > 0 ? ($day['total_pendapatan'] / $maxRevenue) * 100 : 0; @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-[9px] text-slate-500 font-medium">
                        @if($day['total_pendapatan'] > 0) {{ rupiah($day['total_pendapatan']) }} @endif
                    </span>
                    <div class="w-full rounded-t-lg transition-all duration-500"
                         style="height: {{ max($barHeight, $day['total_pendapatan'] > 0 ? 8 : 2) }}%;
                                background: {{ $day['total_pendapatan'] > 0 ? 'linear-gradient(180deg, #0B5CFF, #063B9E)' : '#e2e8f0' }}">
                    </div>
                    <span class="text-[10px] text-slate-500 font-semibold">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- PESANAN TERBARU --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Pesanan Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">8 pesanan yang paling baru masuk</p>
                </div>
                <a href="{{ route('admin.pesanan.index') }}" class="text-xs font-bold text-[#0B5CFF] hover:underline">Semua →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                    <a href="{{ route('admin.pesanan.show', $order->id) }}" class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition">
                        <div>
                            <p class="text-xs font-bold text-slate-900">{{ $order->order_number }}</p>
                            <p class="text-[11px] text-slate-500">{{ $order->user?->name ?? '-' }} &mdash; {{ tgl_indo($order->created_at) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-900">{{ rupiah($order->total_amount) }}</p>
                            <span @class([
                                'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold',
                                'bg-amber-100 text-amber-800' => in_array($order->status, ['pending', 'awaiting_payment']),
                                'bg-blue-100 text-blue-800'   => in_array($order->status, ['paid', 'processing', 'packed']),
                                'bg-sky-100 text-sky-800'     => $order->status === 'shipped',
                                'bg-emerald-100 text-emerald-800' => in_array($order->status, ['delivered', 'completed']),
                                'bg-rose-100 text-rose-800'   => in_array($order->status, ['cancelled', 'refunded', 'returned']),
                            ])>{{ ucfirst($order->status) }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-center py-8 text-xs text-slate-500">Belum ada pesanan.</p>
                @endforelse
            </div>
        </div>

        {{-- STOK KRITIS --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Stok Kritis (&le; 5 unit)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Varian produk yang perlu segera direstok</p>
                </div>
                <a href="{{ route('admin.laporan.inventaris') }}" class="text-xs font-bold text-rose-600 hover:underline">Semua →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($criticalStock as $variant)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ $variant->product?->name ?? 'Produk Dihapus' }}</p>
                            <p class="text-[11px] text-slate-500">{{ $variant->sku }} &mdash; {{ $variant->name }}</p>
                        </div>
                        <div class="ml-3 shrink-0">
                            <span @class([
                                'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black',
                                'bg-rose-100 text-rose-700' => $variant->stock === 0,
                                'bg-amber-100 text-amber-700' => $variant->stock > 0 && $variant->stock <= 3,
                                'bg-orange-100 text-orange-700' => $variant->stock > 3,
                            ])>
                                {{ $variant->stock }} unit
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center py-8">
                        <svg class="w-8 h-8 text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-slate-500">Semua produk memiliki stok yang cukup.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
