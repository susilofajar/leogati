<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LEOGATISTORE') }} — @yield('title', 'Autentikasi Pengguna')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts and CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#F7F9FC] flex flex-col justify-center py-12 sm:px-6 lg:px-8 antialiased text-slate-800 selection:bg-[#0B5CFF] selection:text-white">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-linear-to-br from-[#0B5CFF] to-[#071A3D] flex items-center justify-center text-white shadow-lg shadow-blue-500/25">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div class="text-left">
                <span class="text-2xl font-black tracking-tight text-[#071A3D]">LEOGATI<span class="text-[#0B5CFF]">STORE</span></span>
                <p class="text-[11px] tracking-wider font-bold text-slate-500 uppercase -mt-1">Technology Commerce</p>
            </div>
        </a>
        <h2 class="text-xl font-extrabold text-slate-900">
            @yield('heading', 'Selamat Datang di LEOGATISTORE')
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            @yield('subheading', 'Akses akun belanja teknologi resmi dan simulasi PC Builder Anda')
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 shadow-xl shadow-slate-200/50 rounded-2xl border border-slate-100 sm:px-10">
            @yield('content')
        </div>

        <div class="mt-6 text-center text-xs text-slate-500">
            <a href="{{ route('home') }}" class="font-semibold text-[#0B5CFF] hover:underline flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda Storefront
            </a>
        </div>
    </div>

</body>
</html>
