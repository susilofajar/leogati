@extends('layouts.admin')

@section('header_title', 'Tambah Pengguna / Staf Baru')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Tambah Akun Pengguna / Staf</h2>
            <p class="text-xs text-slate-500 mt-1">Buat akun staf operasional baru lengkap dengan penetapan hak akses dan peran (RBAC).</p>
        </div>
        <a href="{{ route('admin.pengguna.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <form action="{{ route('admin.pengguna.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Fauzi"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block font-bold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="staf.gudang@leogati.store"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('email') border-rose-500 @enderror">
                    @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block font-bold text-slate-700 mb-1.5">Password Awal <span class="text-rose-500">*</span></label>
                <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('password') border-rose-500 @enderror">
                @error('password') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2">
                <label class="block font-bold text-slate-700 mb-2">Pilih Peran Sistem (Role) <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach($roles as $role)
                        <label class="p-3 rounded-xl border border-slate-200 hover:border-[#0B5CFF] bg-slate-50/50 flex items-start gap-3 cursor-pointer transition">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                {{ is_array(old('roles')) && in_array($role->id, old('roles')) ? 'checked' : '' }}
                                class="mt-0.5 w-4 h-4 text-[#0B5CFF] rounded focus:ring-[#0B5CFF]">
                            <div>
                                <span class="font-bold text-slate-900 block">{{ $role->display_name }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $role->name }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('roles') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.pengguna.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-bold rounded-xl transition shadow-xs">
                    Buat Akun Pengguna
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
