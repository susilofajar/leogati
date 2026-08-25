@extends('layouts.guest')

@section('title', 'Masuk ke Akun')
@section('heading', 'Masuk ke Akun Anda')
@section('subheading', 'Gunakan email dan kata sandi yang telah terdaftar')

@section('content')
<form action="{{ route('login') }}" method="POST" class="space-y-4">
    @csrf

    <!-- EMAIL -->
    <div>
        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
            Alamat Email <span class="text-rose-500">*</span>
        </label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
            placeholder="nama@email.com"
            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('email') border-rose-300 bg-rose-50/30 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition">
        @error('email')
            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- PASSWORD -->
    <div>
        <div class="flex items-center justify-between mb-1">
            <label for="password" class="block text-xs font-bold text-slate-700">
                Kata Sandi <span class="text-rose-500">*</span>
            </label>
            <a href="#" class="text-[11px] font-semibold text-[#0B5CFF] hover:underline">
                Lupa kata sandi?
            </a>
        </div>
        <input type="password" name="password" id="password" required
            placeholder="••••••••"
            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('password') border-rose-300 bg-rose-50/30 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition">
        @error('password')
            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- REMEMBER ME -->
    <div class="flex items-center">
        <input type="checkbox" name="remember" id="remember" value="1"
            class="w-4 h-4 rounded border-slate-300 text-[#0B5CFF] focus:ring-[#0B5CFF]">
        <label for="remember" class="ml-2 block text-xs text-slate-600">
            Ingat saya di perangkat ini
        </label>
    </div>

    <!-- SUBMIT BUTTON -->
    <div class="pt-2">
        <button type="submit"
            class="w-full py-3 px-4 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-extrabold rounded-xl shadow-md transition">
            Masuk ke Akun
        </button>
    </div>

    <!-- REGISTER LINK -->
    <div class="text-center pt-2 border-t border-slate-100">
        <p class="text-xs text-slate-600">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-[#0B5CFF] hover:underline ml-1">
                Daftar sekarang
            </a>
        </p>
    </div>
</form>

<!-- DEMO CREDENTIALS HELPER -->
<div class="mt-6 p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] text-slate-600 space-y-1">
    <p class="font-bold text-slate-800">Akun Demo Cepat:</p>
    <p>👑 <strong>Super Admin:</strong> <code class="text-blue-600">superadmin@leogati.store</code> / <code class="text-slate-800">password</code></p>
    <p>🛠 <strong>Admin Operasional:</strong> <code class="text-blue-600">admin@leogati.store</code> / <code class="text-slate-800">password</code></p>
    <p>👤 <strong>Pelanggan Demo:</strong> <code class="text-blue-600">pelanggan@example.com</code> / <code class="text-slate-800">password</code></p>
</div>
@endsection
