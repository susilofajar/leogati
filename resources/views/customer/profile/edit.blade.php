@extends('layouts.app')

@section('title', 'Profil & Keamanan Akun - LEOGATISTORE')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- BREADCRUMBS --}}
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-[#0B5CFF]">Akun Saya</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Profil & Keamanan</span>
    </nav>

    {{-- PAGE HEADER --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-linear-to-br from-[#0B5CFF] to-[#071A3D] text-white flex items-center justify-center font-bold text-xl uppercase shadow-md shadow-blue-500/20">
                {{ substr($user->name, 0, 2) }}
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">{{ $user->name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-[#0B5CFF] border border-blue-100">
                        {{ $user->role_display_name }}
                    </span>
                    <span class="text-xs text-slate-400">
                        Bergabung sejak {{ $user->created_at->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- EDIT PROFILE FORM --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <div class="pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#0B5CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Profil
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui nama dan alamat email akun Anda.</p>
            </div>

            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block font-bold text-slate-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('email') border-rose-500 @enderror">
                    @error('email')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-bold rounded-xl transition shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- CHANGE PASSWORD FORM --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <div class="pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Ganti Password
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Pastikan password minimal 8 karakter dengan kombinasi aman.</p>
            </div>

            <form action="{{ route('customer.profile.password') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block font-bold text-slate-700 mb-1.5">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('current_password') border-rose-500 @enderror">
                    @error('current_password')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block font-bold text-slate-700 mb-1.5">Password Baru</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('password') border-rose-500 @enderror">
                    @error('password')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-xs">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
