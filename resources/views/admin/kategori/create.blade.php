@extends('layouts.admin')

@section('header_title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Tambah Kategori Produk</h2>
            <p class="text-xs text-slate-500 mt-1">Buat grup kategori baru untuk pengelompokan katalog produk hardware.</p>
        </div>
        <a href="{{ route('admin.kategori.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block font-bold text-slate-700 mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Kartu Grafis (GPU)"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="block font-bold text-slate-700 mb-1.5">Slug URL (Opsional)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="otomatis dibuat dari nama"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-slate-900 focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition @error('slug') border-rose-500 @enderror">
                    @error('slug') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="parent_id" class="block font-bold text-slate-700 mb-1.5">Kategori Induk (Parent)</label>
                    <select name="parent_id" id="parent_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF]">
                        <option value="">-- Kategori Utama (Tanpa Induk) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort_order" class="block font-bold text-slate-700 mb-1.5">Urutan Tampilan</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF]">
                </div>
            </div>

            <!-- PILIHAN IKON KATEGORI -->
            <div x-data="{ selectedIcon: '{{ old('icon', 'laptop') }}' }" class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block font-bold text-slate-700">
                        Ikon Visual Kategori <span class="text-slate-400 font-normal">(Pilih ikon representatif untuk tampilan storefront)</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        <span class="text-[11px] text-slate-500 font-medium">Ikon Terpilih:</span>
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 border border-blue-200 text-[#0B5CFF] font-mono font-bold text-[11px]" x-text="selectedIcon || 'default'"></span>
                    </div>
                </div>

                <input type="hidden" name="icon" :value="selectedIcon">

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 p-3 bg-slate-50 border border-slate-200 rounded-2xl max-h-56 overflow-y-auto">
                    @foreach($availableIcons as $iconKey => $meta)
                        <button type="button" @click="selectedIcon = '{{ $iconKey }}'"
                            :class="selectedIcon === '{{ $iconKey }}' ? 'bg-[#0B5CFF] text-white border-[#0B5CFF] shadow-xs ring-2 ring-blue-200' : 'bg-white text-slate-700 border-slate-200 hover:border-blue-300 hover:bg-blue-50/50'"
                            class="flex items-center space-x-2.5 p-2 rounded-xl border text-left transition group">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                                :class="selectedIcon === '{{ $iconKey }}' ? 'bg-white/20 text-white' : 'bg-blue-50 text-[#0B5CFF] group-hover:bg-blue-100'">
                                {!! (new \App\Models\Category(['icon' => $iconKey]))->renderIcon('w-4 h-4') !!}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-[11px] truncate leading-tight">{{ $meta['label'] }}</p>
                                <span class="text-[9px] opacity-75 truncate block leading-tight">{{ $iconKey }}</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="description" class="block font-bold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" id="description" rows="3" placeholder="Penjelasan singkat mengenai kategori produk..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:outline-hidden focus:border-[#0B5CFF]">{{ old('description') }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2 text-slate-700 font-bold cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#0B5CFF] rounded focus:ring-[#0B5CFF]">
                    <span>Aktifkan Kategori (Tampilkan di Storefront)</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.kategori.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-bold rounded-xl transition shadow-xs">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
