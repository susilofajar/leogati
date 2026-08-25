<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Panel Admin — {{ config('app.name', 'LEOGATISTORE') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts and CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-slate-100 flex text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <!-- MOBILE SIDEBAR BACKDROP OVERLAY -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"></div>

    <!-- SIDEBAR (DESKTOP & MOBILE SLIDE-IN) -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#071A3D] text-slate-300 flex flex-col shrink-0 min-h-screen border-r border-slate-800 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- SIDEBAR BRAND -->
        <div class="h-16 flex items-center justify-between px-5 bg-[#040F24] border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-[#0B5CFF] flex items-center justify-center text-white font-black text-sm shadow-xs">
                    L
                </div>
                <div>
                    <span class="text-base font-bold text-white tracking-wide">LEOGATI<span class="text-[#0B5CFF]">STORE</span></span>
                    <span class="block text-[10px] uppercase tracking-wider text-blue-400 font-semibold">Panel Operasional</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- NAVIGATION LINKS (INDONESIAN) -->
        <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto text-xs font-semibold">
            
            <div class="px-2 pb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                Utama
            </div>

            <a href="{{ route('admin.dashboard') }}" 
                class="flex items-center px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <div class="px-2 pt-3.5 pb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                Katalog & Stok
            </div>

            <a href="{{ route('admin.produk.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.produk.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Produk & Varian
            </a>

            <a href="{{ route('admin.kategori.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.kategori.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kategori Produk
            </a>

            <a href="{{ route('admin.merek.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.merek.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Merek Resmi
            </a>

            <a href="{{ route('admin.inventaris.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.inventaris.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Inventaris Stok
            </a>

            <a href="{{ route('admin.gudang.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.gudang.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                Lokasi Gudang
            </a>

            <div class="px-2 pt-3.5 pb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                Operasional Toko
            </div>

            <a href="{{ route('admin.pesanan.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.pesanan.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Pesanan Masuk
            </a>

            <a href="{{ route('admin.nomor_seri.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.nomor_seri.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Nomor Seri Unit
            </a>

            <a href="{{ route('admin.garansi.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.garansi.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Klaim Garansi & RMA
            </a>

            <a href="{{ route('admin.kupon.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.kupon.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kupon Promosi
            </a>

            <a href="{{ route('admin.ulasan.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.ulasan.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                Moderasi Ulasan
            </a>

            <div class="px-2 pt-3.5 pb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                Pengadaan & Vendor
            </div>

            <a href="{{ route('admin.pembelian.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.pembelian.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Purchase Order (PO)
            </a>

            <a href="{{ route('admin.supplier.index') }}" 
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.supplier.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Supplier & Vendor
            </a>

            <div class="px-2 pt-3.5 pb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                Laporan & Analitik
            </div>

            <a href="{{ route('admin.laporan.penjualan') }}"
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.laporan.penjualan') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Laporan Penjualan
            </a>

            <a href="{{ route('admin.laporan.inventaris') }}"
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.laporan.inventaris') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Laporan Inventaris
            </a>

            <a href="{{ route('admin.laporan.pembelian') }}"
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.laporan.pembelian') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Laporan Pembelian
            </a>

            <a href="{{ route('admin.laporan.pelanggan') }}"
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.laporan.pelanggan') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Laporan Pelanggan
            </a>

            <div class="px-2 pt-3.5 pb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                Sistem & Pengguna
            </div>

            <a href="{{ route('admin.pengguna.index') }}"
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.pengguna.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Pengguna & Staf
            </a>

            <a href="{{ route('admin.audit_log.index') }}"
                class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.audit_log.*') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Jejak Audit
            </a>

        </nav>

        <!-- FOOTER USER INFO -->
        <div class="p-4 border-t border-slate-800 bg-[#040F24]">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-8 h-8 rounded-xl bg-[#0B5CFF] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-blue-400 font-semibold">{{ auth()->user()->role_display_name }}</p>
                    </div>
                </div>
            </div>
        </div>

    </aside>

    <!-- CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden min-h-screen">
        
        <!-- HEADER -->
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between shadow-2xs sticky top-0 z-30">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-sm sm:text-base font-extrabold text-slate-800 truncate">
                    @yield('header_title', 'Dashboard Operasional')
                </h1>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-3">
                <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-xs font-bold text-slate-700 flex items-center transition shadow-2xs">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span class="hidden sm:inline">Lihat</span> Storefront
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition flex items-center shadow-2xs">
                        <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- MAIN BODY -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            @if (session('success'))
                <div class="p-3.5 mb-6 text-xs sm:text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center shadow-2xs">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-3.5 mb-6 text-xs sm:text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 flex items-center shadow-2xs">
                    <svg class="w-5 h-5 mr-2 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
