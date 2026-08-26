<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LEOGATISTORE') }} — @yield('title', 'Autentikasi Pengguna')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/tembak ungu rambut grey.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts and CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#F7F9FC] flex flex-col justify-center py-12 sm:px-6 lg:px-8 antialiased text-slate-800 selection:bg-[#0B5CFF] selection:text-white">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="{{ route('home') }}" class="inline-flex items-center justify-center mb-6 group">
            <img src="{{ asset('images/logo/logo.png') }}" alt="{{ config('app.name', 'LEOGATISTORE') }}" class="h-12 sm:h-14 w-auto object-contain transition-transform group-hover:scale-102">
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
