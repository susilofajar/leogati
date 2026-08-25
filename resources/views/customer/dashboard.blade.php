@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- HEADER / PROFILE SUMMARY -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0B5CFF] to-[#071A3D] text-white flex items-center justify-center font-extrabold text-xl shadow-md shadow-blue-500/20">
                {{ substr($user->name, 0, 2) }}
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">Halo, {{ $user->name }}!</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }} &bull; Bergabung sejak {{ tgl_indo($user->created_at) }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('pc_builder.index') }}" class="px-4 py-2.5 bg-blue-50 text-[#0B5CFF] hover:bg-blue-100 font-bold text-xs rounded-xl transition flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Rakit PC Baru
            </a>
            <a href="{{ route('warranty.check') }}" class="px-4 py-2.5 bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold text-xs rounded-xl transition flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Cek Garansi
            </a>
        </div>
    </div>

    <!-- STATS OVERVIEW & QUICK LINKS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <a href="{{ route('customer.orders.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-[#0B5CFF] hover:shadow-md transition group block">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-[#0B5CFF]">Pesanan Aktif</p>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $stats['active_orders'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Sedang dikirim / diproses</p>
        </a>

        <a href="{{ route('customer.orders.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-[#0B5CFF] hover:shadow-md transition group block">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-[#0B5CFF]">Total Riwayat Pesanan</p>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $stats['total_orders'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Semua pesanan</p>
        </a>

        <a href="{{ route('customer.wishlist.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-rose-400 hover:shadow-md transition group block">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-rose-500">Daftar Keinginan</p>
            <p class="text-2xl font-black text-rose-500 mt-2">{{ $stats['wishlist_count'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Produk favorit Anda</p>
        </a>

        <a href="{{ route('customer.builds.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-amber-400 hover:shadow-md transition group block">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-amber-500">Racikan PC Tersimpan</p>
            <p class="text-2xl font-black text-amber-500 mt-2">{{ $stats['saved_builds'] }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Simulasi PC Builder</p>
        </a>

    </div>

    <!-- QUICK ACCESS MENU -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('customer.addresses.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 hover:border-[#0B5CFF] transition flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#0B5CFF] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-900">Buku Alamat</p>
                <p class="text-[10px] text-slate-400">Tujuan pengiriman</p>
            </div>
        </a>

        <a href="{{ route('customer.profile.edit') }}" class="bg-white p-4 rounded-xl border border-slate-200 hover:border-[#0B5CFF] transition flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-900">Profil & Keamanan</p>
                <p class="text-[10px] text-slate-400">Password & email</p>
            </div>
        </a>

        <a href="{{ route('customer.warranty.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 hover:border-[#0B5CFF] transition flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-900">Klaim Garansi</p>
                <p class="text-[10px] text-slate-400">Status klaim RMA</p>
            </div>
        </a>

        <a href="{{ route('customer.notifications.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 hover:border-[#0B5CFF] transition flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-900">Pusat Notifikasi</p>
                <p class="text-[10px] text-slate-400">Info & update pesanan</p>
            </div>
        </a>
    </div>

    <!-- RECENT ORDERS SECTION -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">Pesanan Belanja Terbaru</h2>
                <p class="text-xs text-slate-500 mt-0.5">Status dan rincian transaksi belanja perangkat teknologi Anda</p>
            </div>
            <a href="{{ route('customer.orders.index') }}" class="text-xs font-bold text-[#0B5CFF] hover:underline">Lihat Semua Pesanan &rarr;</a>
        </div>

        @if(isset($recentOrders) && $recentOrders->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($recentOrders as $order)
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-900">{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                                    @if($order->status === 'completed') bg-emerald-50 text-emerald-700
                                    @elseif($order->status === 'cancelled') bg-rose-50 text-rose-700
                                    @else bg-blue-50 text-blue-700 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500">
                                {{ $order->items->count() }} Produk &bull; Total: <strong class="text-slate-900">{{ rupiah($order->total_amount) }}</strong>
                            </p>
                        </div>
                        <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                            class="px-4 py-2 bg-slate-100 hover:bg-[#0B5CFF] text-slate-700 hover:text-white font-bold text-xs rounded-xl transition self-start sm:self-auto">
                            Rincian Pesanan
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- EMPTY STATE -->
            <div class="py-12 text-center space-y-3">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Riwayat Pesanan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Anda belum pernah melakukan pemesanan. Jelajahi katalog laptop dan komponen PC kami untuk mulai berbelanja!
                </p>
                <div class="pt-2">
                    <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-bold text-xs rounded-xl shadow-xs transition inline-block">
                        Mulai Belanja Sekarang
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
