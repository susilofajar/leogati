@extends('layouts.guest')

@section('title', 'Daftar Akun Baru')
@section('heading', 'Buat Akun LEOGATISTORE')
@section('subheading', 'Daftarkan akun untuk mulai berbelanja dan merakit PC impian Anda')

@section('content')
<form action="{{ route('register') }}" method="POST" class="space-y-4">
    @csrf

    <!-- NAMA LENGKAP -->
    <div>
        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
            Nama Lengkap <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
            placeholder="Contoh: Budi Santoso"
            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-300 bg-rose-50/30 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition">
        @error('name')
            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- ALAMAT EMAIL -->
    <div>
        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
            Alamat Email <span class="text-rose-500">*</span>
        </label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required
            placeholder="nama@email.com"
            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('email') border-rose-300 bg-rose-50/30 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition">
        @error('email')
            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- KATA SANDI -->
    <div>
        <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
            Kata Sandi <span class="text-rose-500">*</span>
        </label>
        <input type="password" name="password" id="password" required
            placeholder="Minimal 8 karakter"
            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('password') border-rose-300 bg-rose-50/30 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition">
        @error('password')
            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- KONFIRMASI KATA SANDI -->
    <div>
        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
            Ulangi Kata Sandi <span class="text-rose-500">*</span>
        </label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
            placeholder="Ketik ulang kata sandi"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition">
    </div>

    <!-- TERMS & CONDITIONS -->
    <div>
        <div class="flex items-start">
            <input type="checkbox" name="terms" id="terms" value="1" required
                class="w-4 h-4 mt-0.5 rounded border-slate-300 text-[#0B5CFF] focus:ring-[#0B5CFF]">
            <label for="terms" class="ml-2 block text-xs text-slate-600 leading-tight">
                Saya menyetujui <a href="#" class="text-[#0B5CFF] font-semibold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-[#0B5CFF] font-semibold hover:underline">Kebijakan Privasi</a> LEOGATISTORE.
            </label>
        </div>
        @error('terms')
            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- SUBMIT BUTTON -->
    <div class="pt-2">
        <button type="submit"
            class="w-full py-3 px-4 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-extrabold rounded-xl shadow-md transition">
            Daftar Akun Sekarang
        </button>
    </div>

    <!-- LOGIN LINK -->
    <div class="text-center pt-2 border-t border-slate-100">
        <p class="text-xs text-slate-600">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-[#0B5CFF] hover:underline ml-1">
                Masuk di sini
            </a>
        </p>
    </div>
</form>
@endsection
