@extends('layouts.admin')

@section('header_title', 'Ubah Data Produk')

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Ubah Data Produk: {{ $product->name }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui harga jual, stok gudang, atau deskripsi perangkat</p>
        </div>

        <a href="{{ route('admin.produk.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('admin.produk.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- SECTION 1: INFORMASI DASAR -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF] border-b border-slate-100 pb-2">
                1. Informasi Dasar Produk
            </h3>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                    Nama Lengkap Produk <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                @error('name')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border @error('category_id') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="brand_id" class="block text-xs font-bold text-slate-700 mb-1">
                        Merek Resmi <span class="text-rose-500">*</span>
                    </label>
                    <select name="brand_id" id="brand_id" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border @error('brand_id') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="warranty_period_months" class="block text-xs font-bold text-slate-700 mb-1">
                        Masa Garansi Resmi (Bulan) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="warranty_period_months" id="warranty_period_months" value="{{ old('warranty_period_months', $product->warranty_period_months) }}" required min="0" max="120"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 mb-1">
                        Status Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Aktif (Tampil di Toko)</option>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft (Disembunyikan)</option>
                        <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Arsip</option>
                    </select>
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-300 text-[#0B5CFF] focus:ring-[#0B5CFF]">
                        <span>Tandai Produk Unggulan</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- SECTION 2: VARIAN UTAMA & HARGA -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF] border-b border-slate-100 pb-2">
                2. Varian Utama, Harga & Stok Fisik
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="sku" class="block text-xs font-bold text-slate-700 mb-1">
                        Kode SKU Unik <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $defaultVariant ? $defaultVariant->sku : '') }}" required
                        class="w-full px-3.5 py-2 bg-slate-50 border @error('sku') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs font-mono uppercase focus:outline-hidden focus:border-[#0B5CFF]">
                    @error('sku')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weight_grams" class="block text-xs font-bold text-slate-700 mb-1">
                        Berat Produk (Gram) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="weight_grams" id="weight_grams" value="{{ old('weight_grams', $defaultVariant ? $defaultVariant->weight_grams : 1000) }}" required min="1"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold text-slate-700 mb-1">
                        Harga Jual (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price', $defaultVariant ? (int)$defaultVariant->price : 0) }}" required min="0" step="1000"
                        class="w-full px-3.5 py-2 bg-slate-50 border @error('price') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs font-bold focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <div>
                    <label for="cost_price" class="block text-xs font-bold text-slate-700 mb-1">
                        Harga Modal / HPP (Rp)
                    </label>
                    <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price', $defaultVariant ? (int)$defaultVariant->cost_price : 0) }}" min="0" step="1000"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold text-slate-700 mb-1">
                        Stok Fisik Gudang <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $defaultVariant ? $defaultVariant->stock : 0) }}" required min="0"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold focus:outline-hidden focus:border-[#0B5CFF]">
                </div>
            </div>
        </div>

        <!-- SECTION 3: FOTO & GALERI PRODUK -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-5"
            x-data="{
                isDropping: false,
                files: [],
                primaryImageId: {{ $product->primaryImage ? $product->primaryImage->id : 'null' }},
                deletedImages: [],
                errorMessage: '',
                
                handleFiles(incomingFiles) {
                    this.errorMessage = '';
                    for (let i = 0; i < incomingFiles.length; i++) {
                        const file = incomingFiles[i];
                        if (!file.type.startsWith('image/')) {
                            this.errorMessage = 'Hanya berkas gambar (JPG, PNG, WEBP, GIF) yang diperbolehkan.';
                            continue;
                        }
                        if (file.size > 5 * 1024 * 1024) {
                            this.errorMessage = 'Ukuran berkas ' + file.name + ' melebihi batas 5MB.';
                            continue;
                        }
                        this.files.push({
                            file: file,
                            url: URL.createObjectURL(file),
                            name: file.name,
                            size: (file.size / 1024 / 1024).toFixed(2) + ' MB'
                        });
                    }
                    this.syncInput();
                },

                removeNewFile(index) {
                    URL.revokeObjectURL(this.files[index].url);
                    this.files.splice(index, 1);
                    this.syncInput();
                },

                toggleDelete(id) {
                    const idx = this.deletedImages.indexOf(id);
                    if (idx > -1) {
                        this.deletedImages.splice(idx, 1);
                    } else {
                        this.deletedImages.push(id);
                        if (this.primaryImageId === id) {
                            this.primaryImageId = null;
                        }
                    }
                },

                isDeleted(id) {
                    return this.deletedImages.includes(id);
                },

                syncInput() {
                    const dt = new DataTransfer();
                    this.files.forEach(f => dt.items.add(f.file));
                    this.$refs.fileInput.files = dt.files;
                }
            }">

            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF]">
                    3. Foto & Galeri Produk
                </h3>
                <span class="text-[11px] text-slate-400">Kelola foto yang sudah ada atau unggah foto tambahan</span>
            </div>

            <!-- FOTO YANG SUDAH ADA DI SISTEM -->
            @if($product->images && $product->images->count() > 0)
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                        <span>Foto Produk Saat Ini ({{ $product->images->count() }})</span>
                        <span class="text-[11px] font-normal text-slate-500">Pilih foto utama atau tandai untuk dihapus</span>
                    </div>

                    <input type="hidden" name="primary_image_id" :value="primaryImageId">

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($product->images as $img)
                            <div class="relative group rounded-xl border p-2 transition"
                                :class="isDeleted({{ $img->id }}) ? 'border-rose-300 bg-rose-50/50 opacity-60' : (primaryImageId === {{ $img->id }} ? 'border-[#0B5CFF] ring-2 ring-blue-200 bg-blue-50/30' : 'border-slate-200 bg-slate-50')">
                                
                                <!-- CHECKBOX DELETE (HIDDEN SYNC) -->
                                <template x-if="isDeleted({{ $img->id }})">
                                    <input type="hidden" name="delete_images[]" value="{{ $img->id }}">
                                </template>

                                <!-- THUMBNAIL -->
                                <div class="w-full h-28 rounded-lg overflow-hidden bg-white flex items-center justify-center relative border border-slate-100">
                                    <img src="{{ asset($img->image_path) }}" alt="Foto Produk" class="max-h-full max-w-full object-contain">
                                    
                                    <!-- BADGE UTAMA -->
                                    <template x-if="primaryImageId === {{ $img->id }} && !isDeleted({{ $img->id }})">
                                        <span class="absolute top-1.5 left-1.5 px-2 py-0.5 bg-[#0B5CFF] text-white text-[9px] font-extrabold rounded-md shadow-xs flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            UTAMA
                                        </span>
                                    </template>

                                    <!-- BADGE DIHAPUS -->
                                    <template x-if="isDeleted({{ $img->id }})">
                                        <span class="absolute top-1.5 left-1.5 px-2 py-0.5 bg-rose-600 text-white text-[9px] font-extrabold rounded-md shadow-xs">
                                            AKAN DIHAPUS
                                        </span>
                                    </template>

                                    <!-- TOGGLE DELETE BUTTON -->
                                    <button type="button" @click.stop="toggleDelete({{ $img->id }})"
                                        class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full flex items-center justify-center text-xs shadow-xs transition"
                                        :class="isDeleted({{ $img->id }}) ? 'bg-slate-700 hover:bg-slate-900 text-white' : 'bg-rose-600 hover:bg-rose-700 text-white'"
                                        :title="isDeleted({{ $img->id }}) ? 'Batalkan hapus' : 'Hapus foto ini'">
                                        <span x-text="isDeleted({{ $img->id }}) ? '↺' : '&times;'"></span>
                                    </button>
                                </div>

                                <!-- ACTIONS -->
                                <div class="mt-2 flex items-center justify-between text-[10px]">
                                    <template x-if="!isDeleted({{ $img->id }})">
                                        <button type="button" @click.stop="primaryImageId = {{ $img->id }}"
                                            class="font-bold transition"
                                            :class="primaryImageId === {{ $img->id }} ? 'text-[#0B5CFF]' : 'text-slate-500 hover:text-[#0B5CFF] underline'">
                                            <span x-text="primaryImageId === {{ $img->id }} ? '✓ Cover Utama' : 'Set Jadi Utama'"></span>
                                        </button>
                                    </template>

                                    <template x-if="isDeleted({{ $img->id }})">
                                        <span class="text-rose-600 font-bold">Ditandai hapus</span>
                                    </template>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- DRAG & DROP UNTUK TAMBAH FOTO BARU -->
            <div class="space-y-2 pt-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                    <span>Unggah Foto Tambahan</span>
                    <span class="text-[11px] font-normal text-slate-500">Tarik & letakkan foto baru di area bawah</span>
                </div>

                <div 
                    @dragover.prevent="isDropping = true"
                    @dragleave.prevent="isDropping = false"
                    @drop.prevent="isDropping = false; handleFiles($event.dataTransfer.files)"
                    @click="$refs.fileInput.click()"
                    :class="isDropping ? 'border-[#0B5CFF] bg-blue-50/70 ring-4 ring-blue-100' : 'border-slate-300 hover:border-[#0B5CFF] hover:bg-slate-50/80'"
                    class="border-2 border-dashed rounded-2xl p-6 text-center cursor-pointer transition-all duration-200 group relative">

                    <input type="file" x-ref="fileInput" name="images[]" multiple accept="image/*" class="hidden"
                        @change="handleFiles($event.target.files)">

                    <div class="flex flex-col items-center justify-center space-y-2">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0B5CFF] group-hover:bg-[#0B5CFF] group-hover:text-white flex items-center justify-center transition duration-200 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        <div class="space-y-0.5 text-center">
                            <p class="text-xs font-bold text-slate-800">
                                Tarik dan Lepaskan (Drag & Drop) Foto Tambahan di Sini
                            </p>
                            <p class="text-[11px] text-slate-500">
                                atau <span class="text-[#0B5CFF] underline font-bold group-hover:text-blue-700">pilih berkas gambar baru</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ERROR NOTIFICATION -->
            <div x-show="errorMessage" x-text="errorMessage" class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-semibold"></div>

            @error('images')
                <p class="text-rose-600 text-xs font-bold">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-rose-600 text-xs font-bold">{{ $message }}</p>
            @enderror

            <!-- PREVIEWS GRID (FOTO BARU) -->
            <div x-show="files.length > 0" class="space-y-2 pt-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                    <span>Foto Baru yang Akan Disimpan (<span x-text="files.length"></span>)</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <template x-for="(item, index) in files" :key="index">
                        <div class="relative group rounded-xl border border-slate-200 p-2 bg-slate-50 transition">
                            <!-- THUMBNAIL -->
                            <div class="w-full h-28 rounded-lg overflow-hidden bg-white flex items-center justify-center relative border border-slate-100">
                                <img :src="item.url" class="max-h-full max-w-full object-contain">
                                
                                <!-- REMOVE BUTTON -->
                                <button type="button" @click.stop="removeNewFile(index)"
                                    class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-xs shadow-xs transition"
                                    title="Batalkan foto ini">
                                    &times;
                                </button>
                            </div>

                            <!-- FILE DETAILS -->
                            <div class="mt-2 text-[10px] space-y-0.5">
                                <p class="font-bold text-slate-800 truncate" x-text="item.name"></p>
                                <p class="text-slate-400" x-text="item.size"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- SECTION 4: DESKRIPSI -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF] border-b border-slate-100 pb-2">
                4. Deskripsi & Rincian Produk
            </h3>

            <div>
                <label for="short_description" class="block text-xs font-bold text-slate-700 mb-1">
                    Ringkasan Singkat (Muncul di Kartu Katalog)
                </label>
                <textarea name="short_description" id="short_description" rows="2"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1">
                    Deskripsi Lengkap & Keunggulan Produk
                </label>
                <textarea name="description" id="description" rows="5"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- SUBMIT BUTTONS -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('admin.produk.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-extrabold rounded-xl shadow-md transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
