@extends('layouts.admin')

@section('header_title', 'Edit Slide Hero Background')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- BREADCRUMB / BACK LINK -->
    <div>
        <a href="{{ route('admin.banner-hero.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-[#0B5CFF] transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Banner Hero
        </a>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Edit Slide Background Hero</h2>
            <p class="text-xs text-slate-500 mt-1">Perbarui gambar background, urutan, atau teks promosi pada slider storefront.</p>
        </div>

        <form action="{{ route('admin.banner-hero.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- PRATINJAU GAMBAR SAAT INI -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-800">Pratinjau Gambar Background Saat Ini</label>
                <div class="w-full h-48 rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 relative shadow-sm">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Hero Background' }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent flex items-end p-4">
                        <span class="text-xs text-white/90 font-medium">Gambar aktif yang tampil di storefront</span>
                    </div>
                </div>
            </div>

            <!-- GAMBAR BACKGROUND BARU -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-800">Ganti Gambar Background (Opsional)</label>
                <input type="file" name="image" accept="image/*"
                    class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#0B5CFF] hover:file:bg-blue-100 border border-slate-300 rounded-xl p-1 bg-slate-50 focus:outline-hidden">
                <p class="text-[11px] text-slate-500">Biarkan kosong jika tidak ingin mengganti gambar saat ini. Ukuran rekomendasi: 1920x1080 (Maksimal 4MB).</p>
                @error('image')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- JUDUL -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-800">Judul Utama Slide (Opsional)</label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" placeholder="Contoh: Promo Komponen Gaming Terbaik"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
                    @error('title')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- BADGE TEKS -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-800">Teks Badge / Tagline (Opsional)</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}" placeholder="Contoh: Diskon Hingga 30%"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
                    @error('badge_text')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SUBJUDUL / DESKRIPSI -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-800">Subjudul / Deskripsi Pendek (Opsional)</label>
                <textarea name="subtitle" rows="3" placeholder="Deskripsi singkat yang akan tampil di atas background slide..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">{{ old('subtitle', $banner->subtitle) }}</textarea>
                @error('subtitle')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- TEKS TOMBOL CTA -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-800">Teks Tombol Aksi / CTA (Opsional)</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" placeholder="Contoh: Belanja Sekarang"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
                    @error('button_text')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TAUTAN URL CTA -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-800">URL Tujuan Tombol CTA (Opsional)</label>
                    <input type="text" name="button_url" value="{{ old('button_url', $banner->button_url) }}" placeholder="Contoh: /produk atau https://leogati.store/kategori/komponen-pc"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
                    @error('button_url')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2 border-t border-slate-100">
                <!-- URUTAN SORTS -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-800">Urutan Tampil (Sort Order)</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
                    <p class="text-[11px] text-slate-500">Angka lebih kecil tampil lebih awal (misal: 1, 2, 3).</p>
                    @error('sort_order')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- STATUS AKTIF -->
                <div class="flex items-center space-x-3 pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#0B5CFF] border-slate-300 rounded-sm focus:ring-[#0B5CFF]">
                    <label for="is_active" class="text-xs font-bold text-slate-800 cursor-pointer">
                        Aktifkan Slide Background Hero Ini
                    </label>
                </div>
            </div>

            <!-- BUTTON ACTIONS -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.banner-hero.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-bold text-xs rounded-xl transition shadow-md">
                    Perbarui Slide Background
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
