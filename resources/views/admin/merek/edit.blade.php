@extends('layouts.admin')

@section('header_title', 'Ubah Merek: ' . $brand->name)

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Ubah Merek Resmi</h2>
            <p class="text-xs text-slate-500 mt-1">Perbarui nama, slug, dan profil merek mitra hardware.</p>
        </div>
        <a href="{{ route('admin.merek.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <form action="{{ route('admin.merek.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block font-bold text-slate-700 mb-1.5">Nama Merek <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="block font-bold text-slate-700 mb-1.5">Slug URL</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $brand->slug) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('slug') border-rose-500 @enderror">
                    @error('slug') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- LOGO PREVIEW & UPLOAD -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block font-bold text-slate-700">Logo Resmi Brand Saat Ini:</label>
                        <span class="text-[10px] text-slate-400">Tampil di etalase beranda dan katalog</span>
                    </div>
                    <div class="h-9 px-3.5 rounded-xl bg-white border border-slate-200 flex items-center justify-center shadow-2xs">
                        {!! $brand->renderLogo('h-6 w-auto max-w-[100px] object-contain') !!}
                    </div>
                </div>

                @if(auth()->user() && auth()->user()->isSuperAdmin())
                    <div class="pt-2 border-t border-slate-200/80">
                        <div class="flex items-center justify-between mb-1">
                            <label for="logo" class="block font-bold text-slate-700">Ganti Berkas Logo:</label>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Khusus Super Admin
                            </span>
                        </div>
                        <input type="file" name="logo" id="logo" accept="image/*"
                            class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF]">
                        <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah logo saat ini.</p>
                        @error('logo') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    <div class="pt-2 border-t border-slate-200/80 text-[11px] text-slate-500">
                        🔒 Penggantian logo resmi brand dibatasi khusus untuk pengguna berwenang <strong>Super Admin</strong>.
                    </div>
                @endif
            </div>

            <div>
                <label for="description" class="block font-bold text-slate-700 mb-1.5">Deskripsi Singkat Brand</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF]">{{ old('description', $brand->description) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2 text-slate-700 font-bold cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#0B5CFF] rounded focus:ring-[#0B5CFF]">
                    <span>Aktifkan Merek (Tampilkan di Filter & Storefront)</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.merek.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-bold rounded-xl transition shadow-xs">
                    Perbarui Merek
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
