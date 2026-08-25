<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LEOGATISTORE') }} — @yield('title', 'Pusat Laptop, Komputer & Komponen PC Resmi')</title>
    <meta name="description" content="@yield('meta_description', 'LEOGATISTORE adalah pusat belanja laptop, PC rakitan, komponen komputer, monitor, dan aksesoris teknologi bergaransi resmi di Indonesia.')">

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
<body class="min-h-screen flex flex-col antialiased bg-[#F7F9FC] text-slate-800 selection:bg-[#0B5CFF] selection:text-white pb-16 md:pb-0" x-data="{ mobileMenuOpen: false }">

    <!-- TOPBAR (DESKTOP & TABLET) -->
    <div class="bg-[#071A3D] text-slate-300 text-xs py-2 px-4 border-b border-slate-800 hidden sm:block">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center text-blue-400 font-medium">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    100% Produk Original & Bergaransi Resmi
                </span>
                <span class="text-slate-600">|</span>
                <span class="text-slate-400">Pusat Layanan: 0812-3456-7890</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('warranty.check') }}" class="hover:text-white transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Cek Garansi Resmi
                </a>
                <span class="text-slate-600">|</span>
                <a href="{{ route('pc_builder.index') }}" class="text-amber-400 hover:text-amber-300 font-semibold flex items-center transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Simulasi PC Builder
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between gap-3">
                
                <!-- BRAND LOGO -->
                <div class="flex items-center space-x-2">
                    <button @click="mobileMenuOpen = true" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition -ml-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-linear-to-br from-[#0B5CFF] to-[#071A3D] flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg sm:text-xl font-extrabold tracking-tight text-[#071A3D]">LEOGATI<span class="text-[#0B5CFF]">STORE</span></span>
                            <p class="hidden sm:block text-[10px] tracking-wider font-semibold text-slate-500 uppercase -mt-1">Technology Commerce</p>
                        </div>
                    </a>
                </div>

                <!-- SEARCH BAR (DESKTOP) -->
                <div class="hidden md:flex flex-1 max-w-xl mx-4">
                    <form action="{{ route('products.index') }}" method="GET" class="w-full relative">
                        <div class="relative flex items-center">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_placeholder') }}" 
                                class="w-full pl-11 pr-24 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white focus:ring-3 focus:ring-blue-100 transition shadow-inner">
                            <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <button type="submit" class="absolute right-1.5 px-3 py-1 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-lg transition shadow-xs">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>

                <!-- USER ACTIONS -->
                <div class="flex items-center space-x-1 sm:space-x-3">
                    
                    <!-- WISHLIST (DESKTOP) -->
                    @php
                        $navbarWishlistCount = auth()->check() ? auth()->user()->wishlists()->count() : 0;
                    @endphp
                    <a href="{{ route('customer.wishlist.index') }}" class="hidden sm:flex p-2 text-slate-600 hover:text-rose-600 hover:bg-slate-50 rounded-xl transition relative" title="Daftar Keinginan">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        @if($navbarWishlistCount > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-rose-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center">
                                {{ $navbarWishlistCount > 9 ? '9+' : $navbarWishlistCount }}
                            </span>
                        @endif
                    </a>

                    <!-- COMPARISON -->
                    @php
                        $navbarCompareCount = count(session('compare_products', []));
                    @endphp
                    <a href="{{ route('comparison.index') }}" class="p-2 text-slate-600 hover:text-[#0B5CFF] hover:bg-slate-50 rounded-xl transition relative" title="Perbandingan Produk">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        @if($navbarCompareCount > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-[#0B5CFF] text-white rounded-full text-[10px] font-bold flex items-center justify-center">
                                {{ $navbarCompareCount }}
                            </span>
                        @endif
                    </a>

                    <!-- NOTIFICATIONS -->
                    @auth
                        @php $navbarUnreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                        <a href="{{ route('customer.notifications.index') }}" class="p-2 text-slate-600 hover:text-[#0B5CFF] hover:bg-slate-50 rounded-xl transition relative" title="Notifikasi Saya">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @if($navbarUnreadCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 bg-rose-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center animate-pulse">
                                    {{ $navbarUnreadCount > 9 ? '9+' : $navbarUnreadCount }}
                                </span>
                            @endif
                        </a>
                    @endauth

                    <!-- CART -->
                    <a href="{{ route('cart.index') }}" class="p-2 text-slate-600 hover:text-[#0B5CFF] hover:bg-slate-50 rounded-xl transition relative" title="Keranjang Belanja">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </a>

                    <!-- AUTH BUTTONS / USER MENU -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 p-1.5 rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#0B5CFF] text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                                <div class="hidden lg:block text-left pr-1">
                                    <p class="text-xs font-bold text-slate-800 truncate max-w-[100px]">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-blue-600 font-semibold">{{ auth()->user()->role_display_name }}</p>
                                </div>
                                <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                                
                                <div class="px-4 py-2.5 border-b border-slate-100 bg-slate-50/50">
                                    <p class="text-[11px] text-slate-400">Masuk sebagai:</p>
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        Panel Admin
                                    </a>
                                @endif

                                <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard Pelanggan
                                </a>

                                <a href="{{ route('customer.orders.index') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Pesanan Belanja Saya
                                </a>

                                <a href="{{ route('customer.wishlist.index') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                    <svg class="w-4 h-4 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    Daftar Keinginan (Wishlist)
                                </a>

                                <a href="{{ route('customer.builds.index') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                    <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                    Racikan PC Tersimpan
                                </a>

                                <a href="{{ route('customer.addresses.index') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Buku Alamat Pengiriman
                                </a>

                                <a href="{{ route('customer.warranty.index') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Klaim Garansi Saya
                                </a>

                                <a href="{{ route('customer.profile.edit') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-medium">
                                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil & Keamanan
                                </a>

                                <form action="{{ route('logout') }}" method="POST" class="border-t border-slate-100 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-bold">
                                        <svg class="w-4 h-4 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-1.5">
                            <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs font-bold text-slate-700 hover:text-[#0B5CFF] rounded-xl transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="hidden sm:inline-block px-3.5 py-1.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-xs transition">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- MOBILE SEARCH INPUT -->
            <div class="mt-2.5 md:hidden">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari laptop, GPU, prosesor, RAM..." 
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- CATEGORIES & NAVIGATION SUBBAR (DESKTOP) -->
        <nav class="bg-white border-t border-slate-100 hidden md:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-6 py-2 text-xs font-semibold text-slate-600 overflow-x-auto">
                    <a href="{{ route('home') }}" class="hover:text-[#0B5CFF] py-1 border-b-2 {{ request()->routeIs('home') ? 'border-[#0B5CFF] text-[#0B5CFF]' : 'border-transparent' }} transition shrink-0">
                        Beranda
                    </a>
                    <a href="{{ route('products.index') }}" class="hover:text-[#0B5CFF] py-1 border-b-2 {{ request()->routeIs('products.*') ? 'border-[#0B5CFF] text-[#0B5CFF]' : 'border-transparent' }} transition shrink-0">
                        Katalog Lengkap
                    </a>
                    <a href="{{ route('pc_builder.index') }}" class="text-[#0B5CFF] font-bold flex items-center hover:text-[#063B9E] py-1 transition shrink-0">
                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>
                        Simulasi PC Builder
                    </a>
                    <a href="{{ route('warranty.check') }}" class="hover:text-[#0B5CFF] py-1 transition shrink-0">
                        Cek Garansi Resmi
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('categories.show', 'laptop') }}" class="hover:text-[#0B5CFF] py-1 transition shrink-0">Laptop</a>
                    <a href="{{ route('categories.show', 'komponen-pc') }}" class="hover:text-[#0B5CFF] py-1 transition shrink-0">Komponen PC</a>
                    <a href="{{ route('categories.show', 'monitor') }}" class="hover:text-[#0B5CFF] py-1 transition shrink-0">Monitor</a>
                    <a href="{{ route('categories.show', 'media-penyimpanan') }}" class="hover:text-[#0B5CFF] py-1 transition shrink-0">Storage SSD</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- MOBILE SLIDE-OVER DRAWER -->
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden md:hidden">
        <div x-show="mobileMenuOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="mobileMenuOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

        <div class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-2xl flex flex-col z-50"
            x-show="mobileMenuOpen" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            
            <div class="p-4 bg-[#071A3D] text-white flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-[#0B5CFF] flex items-center justify-center font-black text-sm">
                        L
                    </div>
                    <span class="font-extrabold text-base tracking-wide">LEOGATI<span class="text-[#0B5CFF]">STORE</span></span>
                </div>
                <button @click="mobileMenuOpen = false" class="text-slate-400 hover:text-white p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4 text-xs font-semibold">
                <div class="space-y-1">
                    <p class="px-2 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Navigasi Utama</p>
                    <a href="{{ route('home') }}" class="flex items-center px-3 py-2 rounded-xl text-slate-800 hover:bg-slate-100 transition">
                        Beranda
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center px-3 py-2 rounded-xl text-slate-800 hover:bg-slate-100 transition">
                        Katalog Lengkap Produk
                    </a>
                    <a href="{{ route('pc_builder.index') }}" class="flex items-center px-3 py-2 rounded-xl text-[#0B5CFF] font-bold bg-blue-50/70 hover:bg-blue-100 transition">
                        ⚡ Simulasi PC Builder
                    </a>
                    <a href="{{ route('warranty.check') }}" class="flex items-center px-3 py-2 rounded-xl text-slate-800 hover:bg-slate-100 transition">
                        🛡️ Cek Garansi Resmi
                    </a>
                </div>

                <div class="pt-2 border-t border-slate-100 space-y-1">
                    <p class="px-2 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Fitur Belanja</p>
                    <a href="{{ route('customer.wishlist.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-800 hover:bg-slate-100 transition">
                        <span>❤️ Daftar Keinginan</span>
                        @if($navbarWishlistCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white">{{ $navbarWishlistCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('comparison.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-800 hover:bg-slate-100 transition">
                        <span>⚖️ Bandingkan Produk</span>
                        @if($navbarCompareCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#0B5CFF] text-white">{{ $navbarCompareCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex items-center px-3 py-2 rounded-xl text-slate-800 hover:bg-slate-100 transition">
                        🛒 Keranjang Belanja
                    </a>
                </div>

                <div class="pt-2 border-t border-slate-100 space-y-1">
                    <p class="px-2 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kategori Unggulan</p>
                    <a href="{{ route('categories.show', 'laptop') }}" class="block px-3 py-1.5 text-slate-600 hover:text-[#0B5CFF]">Laptop & Ultrabook</a>
                    <a href="{{ route('categories.show', 'komponen-pc') }}" class="block px-3 py-1.5 text-slate-600 hover:text-[#0B5CFF]">Komponen PC</a>
                    <a href="{{ route('categories.show', 'monitor') }}" class="block px-3 py-1.5 text-slate-600 hover:text-[#0B5CFF]">Monitor Gaming</a>
                    <a href="{{ route('categories.show', 'media-penyimpanan') }}" class="block px-3 py-1.5 text-slate-600 hover:text-[#0B5CFF]">Media Penyimpanan SSD</a>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50">
                @auth
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#0B5CFF] text-white font-bold flex items-center justify-center text-xs">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500">{{ auth()->user()->role_display_name }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('customer.dashboard') }}" class="text-center py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full py-2 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold">Keluar</button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="text-center py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700">Masuk</a>
                        <a href="{{ route('register') }}" class="text-center py-2 bg-[#0B5CFF] text-white rounded-xl text-xs font-bold shadow-xs">Daftar</a>
                    </div>
                @endauth
            </div>

        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('success'))
            <div class="p-3.5 mb-4 text-xs sm:text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center shadow-xs">
                <svg class="w-5 h-5 mr-2 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-3.5 mb-4 text-xs sm:text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 flex items-center shadow-xs">
                <svg class="w-5 h-5 mr-2 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="p-3.5 mb-4 text-xs sm:text-sm text-blue-800 rounded-2xl bg-blue-50 border border-blue-200 flex items-center shadow-xs">
                <svg class="w-5 h-5 mr-2 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- MOBILE STICKY BOTTOM NAVIGATION BAR -->
    <div class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200 z-40 md:hidden py-1.5 px-3">
        <div class="grid grid-cols-5 text-center text-[10px] font-bold">
            <a href="{{ route('home') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('home') ? 'text-[#0B5CFF]' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="mt-0.5">Beranda</span>
            </a>

            <a href="{{ route('products.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('products.*') ? 'text-[#0B5CFF]' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span class="mt-0.5">Katalog</span>
            </a>

            <a href="{{ route('pc_builder.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('pc_builder.*') ? 'text-[#0B5CFF]' : 'text-slate-500' }}">
                <div class="w-8 h-8 -mt-3 rounded-full bg-[#0B5CFF] text-white flex items-center justify-center shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                </div>
                <span class="mt-0.5 text-[9px] text-[#0B5CFF]">PC Builder</span>
            </a>

            <a href="{{ route('customer.wishlist.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('customer.wishlist.*') ? 'text-rose-600' : 'text-slate-500' }} relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span class="mt-0.5">Wishlist</span>
                @if($navbarWishlistCount > 0)
                    <span class="absolute top-0.5 right-4 w-3.5 h-3.5 bg-rose-500 text-white rounded-full text-[9px] font-bold flex items-center justify-center">
                        {{ $navbarWishlistCount > 9 ? '9+' : $navbarWishlistCount }}
                    </span>
                @endif
            </a>

            @auth
                <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('customer.*') ? 'text-[#0B5CFF]' : 'text-slate-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="mt-0.5">Akun</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center py-1 text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span class="mt-0.5">Masuk</span>
                </a>
            @endauth
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-[#071A3D] text-slate-400 text-sm mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- BRAND INFO -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 rounded-xl bg-linear-to-br from-[#0B5CFF] to-blue-700 flex items-center justify-center text-white font-bold shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="text-xl font-extrabold text-white">LEOGATI<span class="text-[#0B5CFF]">STORE</span></span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        Platform e-commerce teknologi terintegrasi nomor satu di Indonesia. Menyediakan laptop, komponen PC, perakitan custom PC, dan aksesoris resmi bergaransi penuh.
                    </p>
                    <div class="text-xs text-slate-400 space-y-1.5">
                        <p class="flex items-center"><svg class="w-4 h-4 mr-2 text-[#0B5CFF] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Jakarta Pusat, DKI Jakarta, Indonesia</p>
                        <p class="flex items-center"><svg class="w-4 h-4 mr-2 text-[#0B5CFF] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> cs@leogati.store</p>
                    </div>
                </div>

                <!-- CATEGORIES -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Kategori Produk</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('categories.show', 'laptop') }}" class="hover:text-white transition">Laptop & Ultrabook</a></li>
                        <li><a href="{{ route('pc_builder.index') }}" class="hover:text-white transition">PC Rakitan & Desktop</a></li>
                        <li><a href="{{ route('categories.show', 'komponen-pc') }}" class="hover:text-white transition">Prosesor & Kartu Grafis</a></li>
                        <li><a href="{{ route('categories.show', 'komponen-pc') }}" class="hover:text-white transition">Motherboard & RAM</a></li>
                        <li><a href="{{ route('categories.show', 'media-penyimpanan') }}" class="hover:text-white transition">SSD NVMe & Harddisk</a></li>
                        <li><a href="{{ route('categories.show', 'monitor') }}" class="hover:text-white transition">Monitor Gaming</a></li>
                    </ul>
                </div>

                <!-- SERVICES & WARRANTY -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Layanan & Garansi</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('pc_builder.index') }}" class="hover:text-white transition">Simulasi PC Builder</a></li>
                        <li><a href="{{ route('warranty.check') }}" class="hover:text-white transition">Pengecekan Nomor Seri Garansi</a></li>
                        <li><a href="{{ route('warranty.claim_form') }}" class="hover:text-white transition">Klaim Garansi & RMA</a></li>
                        <li><a href="{{ route('comparison.index') }}" class="hover:text-white transition">Perbandingan Spesifikasi</a></li>
                    </ul>
                </div>

                <!-- SECURITY & PAYMENT METHODS -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Metode Pembayaran Resmi</h3>
                    <p class="text-xs text-slate-400 mb-3">Transaksi aman terenkripsi dengan proteksi bank Indonesia:</p>
                    <div class="flex flex-wrap gap-2 text-xs font-bold text-slate-300">
                        <span class="px-2.5 py-1 bg-slate-800 rounded-lg border border-slate-700">QRIS</span>
                        <span class="px-2.5 py-1 bg-slate-800 rounded-lg border border-slate-700">BCA Virtual Account</span>
                        <span class="px-2.5 py-1 bg-slate-800 rounded-lg border border-slate-700">Mandiri</span>
                        <span class="px-2.5 py-1 bg-slate-800 rounded-lg border border-slate-700">BRI</span>
                        <span class="px-2.5 py-1 bg-slate-800 rounded-lg border border-slate-700">BNI</span>
                    </div>
                </div>

            </div>

            <!-- COPYRIGHT -->
            <div class="pt-8 mt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} LEOGATISTORE. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="{{ route('products.index') }}" class="hover:text-slate-400">Katalog Produk</a>
                    <a href="{{ route('warranty.check') }}" class="hover:text-slate-400">Pusat Garansi</a>
                    <a href="{{ route('pc_builder.index') }}" class="hover:text-slate-400">PC Simulator</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
