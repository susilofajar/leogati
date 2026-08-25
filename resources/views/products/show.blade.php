@extends('layouts.app')

@section('title', $product->name . ' — Spesifikasi & Garansi Resmi')
@section('meta_description', $product->short_description ?? 'Beli ' . $product->name . ' dengan garansi resmi dan harga terbaik di LEOGATISTORE.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10" 
    x-data="{
        selectedVariant: {{ $product->variants->first() ? $product->variants->first()->toJson() : '{}' }},
        quantity: 1,
        formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
    }">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs font-semibold text-slate-500 overflow-x-auto whitespace-nowrap">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#0B5CFF]">Katalog</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-[#0B5CFF]">{{ $product->category->name }}</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- PRODUCT PRIMARY SECTION (GALLERY & DETAILS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT: IMAGES & BADGES -->
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-center min-h-[340px] relative overflow-hidden">
                <div class="w-32 h-32 rounded-3xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center font-black text-4xl shadow-inner">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                @if($product->is_featured)
                    <span class="absolute top-4 left-4 px-3 py-1 bg-amber-400 text-slate-950 text-xs font-black rounded-lg shadow-xs">
                        PRODUK UNGGULAN
                    </span>
                @endif
            </div>

            <!-- OFFICIAL WARRANTY BANNER -->
            <div class="bg-gradient-to-r from-blue-900 to-[#071A3D] text-white p-4 rounded-2xl flex items-center space-x-3.5 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-blue-500/30 text-blue-300 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-extrabold text-white">Garansi Resmi Distributor {{ $product->warranty_period_months }} Bulan</h4>
                    <p class="text-[11px] text-blue-200 leading-tight mt-0.5">Klaim garansi mudah melalui nomor seri produk di seluruh service center resmi Indonesia.</p>
                </div>
            </div>
        </div>

        <!-- RIGHT: PRODUCT OPTIONS & BUY BOX -->
        <div class="lg:col-span-7 space-y-6">
            
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
                
                <!-- BRAND & CATEGORY -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('brands.show', $product->brand->slug) }}" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-[#0B5CFF] text-xs font-bold rounded-lg transition">
                        {{ $product->brand->name }}
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('categories.show', $product->category->slug) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                        {{ $product->category->name }}
                    </a>
                </div>

                <!-- TITLE -->
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                    {{ $product->name }}
                </h1>

                <!-- SKU & STOCK BADGE -->
                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <span class="text-slate-500 font-mono">
                        SKU: <strong class="text-slate-800" x-text="selectedVariant.sku"></strong>
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="inline-flex items-center font-bold"
                        :class="selectedVariant.stock > 0 ? 'text-emerald-700' : 'text-rose-600'">
                        <span class="w-2 h-2 rounded-full mr-1.5"
                            :class="selectedVariant.stock > 0 ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                        <span x-text="selectedVariant.stock > 0 ? 'Stok Tersedia: ' + selectedVariant.stock + ' unit' : 'Stok Habis'"></span>
                    </span>
                </div>

                <!-- PRICE -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400">Harga Resmi</p>
                    <p class="text-3xl font-black text-[#0B5CFF] mt-0.5" x-text="formatRupiah(selectedVariant.price)">
                        {{ rupiah($product->defaultVariant ? $product->defaultVariant->price : 0) }}
                    </p>
                </div>

                <!-- SHORT DESCRIPTION -->
                @if($product->short_description)
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                @endif

                <!-- VARIANTS SELECTOR -->
                @if($product->variants->count() > 1)
                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-700">Pilih Varian Produk:</label>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($product->variants as $variant)
                                <button type="button" 
                                    @click="selectedVariant = {{ $variant->toJson() }}"
                                    class="px-4 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-2"
                                    :class="selectedVariant.id === {{ $variant->id }} ? 'border-[#0B5CFF] bg-blue-50 text-[#0B5CFF] ring-2 ring-blue-100' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'">
                                    <span>{{ $variant->name }}</span>
                                    <span class="text-[10px] text-slate-400">({{ rupiah($variant->price) }})</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- QUANTITY & CTA BUTTONS -->
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4 pt-4 border-t border-slate-100">
                    @csrf
                    <input type="hidden" name="product_variant_id" :value="selectedVariant.id">

                    <div class="flex items-center space-x-4">
                        <label class="text-xs font-bold text-slate-700">Jumlah:</label>
                        <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden">
                            <button type="button" @click="if(quantity > 1) quantity--" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs">-</button>
                            <input type="number" name="quantity" x-model="quantity" min="1" :max="selectedVariant.stock" class="w-12 text-center text-xs font-bold py-1.5 border-none focus:outline-hidden">
                            <button type="button" @click="if(quantity < selectedVariant.stock) quantity++" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs">+</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <button type="submit" :disabled="selectedVariant.stock <= 0"
                            class="w-full py-3.5 px-4 bg-[#0B5CFF] hover:bg-[#063B9E] disabled:bg-slate-200 disabled:text-slate-400 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Tambah ke Keranjang
                        </button>

                        <button type="submit" :disabled="selectedVariant.stock <= 0"
                            class="w-full py-3.5 px-4 bg-[#071A3D] hover:bg-slate-900 disabled:bg-slate-200 disabled:text-slate-400 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center">
                            Beli Sekarang
                        </button>
                    </div>

                </form>

                <!-- WISHLIST & COMPARE SECONDARY ACTIONS -->
                <div class="flex items-center gap-3 pt-2">
                    @auth
                        @php
                            $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                        @endphp
                        <form action="{{ route('wishlist.toggle') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-2 {{ $inWishlist ? 'border-rose-300 bg-rose-50 text-rose-600' : 'border-slate-200 hover:border-rose-300 text-slate-700 hover:text-rose-600 bg-white' }}">
                                <svg class="w-4 h-4" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>{{ $inWishlist ? 'Di Wishlist' : 'Tambah ke Wishlist' }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" 
                            class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 hover:border-rose-300 text-slate-700 hover:text-rose-600 bg-white text-xs font-bold transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>Tambah ke Wishlist</span>
                        </a>
                    @endauth

                    <form action="{{ route('comparison.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl border border-slate-200 hover:border-[#0B5CFF] text-slate-700 hover:text-[#0B5CFF] bg-white text-xs font-bold transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Bandingkan</span>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>

    <!-- DETAILED SPECIFICATIONS & DESCRIPTION -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- SPECIFICATIONS TABLE -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-extrabold text-slate-900">Spesifikasi Teknis Lengkap</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Data terstruktur komponen untuk verifikasi performa dan kompatibilitas sistem</p>
                </div>

                @if($groupedSpecs->count() > 0)
                    <div class="space-y-6">
                        @foreach($groupedSpecs as $groupName => $specs)
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF] mb-3 bg-blue-50/70 px-3 py-1.5 rounded-lg inline-block">
                                    {{ $groupName }}
                                </h3>
                                <div class="divide-y divide-slate-100 text-xs border border-slate-100 rounded-2xl overflow-hidden">
                                    @foreach($specs as $spec)
                                        <div class="grid grid-cols-1 sm:grid-cols-12 p-3 sm:px-4 hover:bg-slate-50/60 transition">
                                            <div class="sm:col-span-5 font-semibold text-slate-600">
                                                {{ $spec->attribute->name }}
                                            </div>
                                            <div class="sm:col-span-7 font-bold text-slate-900 mt-0.5 sm:mt-0">
                                                {{ $spec->value }} {{ $spec->attribute->unit ? '(' . $spec->attribute->unit . ')' : '' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500">Spesifikasi detail perangkat ini tertera lengkap pada buku panduan resmi.</p>
                @endif

                <!-- FULL DESCRIPTION -->
                @if($product->description)
                    <div class="pt-6 border-t border-slate-100 space-y-2">
                        <h3 class="text-sm font-bold text-slate-900">Deskripsi & Keunggulan Produk</h3>
                        <div class="text-xs text-slate-600 leading-relaxed space-y-2">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- REVIEWS & RATINGS SECTION -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6" id="ulasan">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Ulasan & Penilaian Pembeli</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Ulasan asli dari pelanggan yang telah membeli produk ini (Verified Purchase)</p>
                    </div>

                    <div class="flex items-center space-x-3 bg-amber-50/80 px-4 py-2 rounded-2xl border border-amber-100">
                        <span class="text-2xl font-black text-amber-600">{{ $product->average_rating }}</span>
                        <div>
                            <div class="flex text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-[10px] font-bold text-slate-500">{{ $product->reviews_count }} Ulasan Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <!-- WRITE REVIEW FORM (IF ELIGIBLE) -->
                @if($reviewEligibility['can_review'])
                    <div class="p-6 rounded-2xl bg-blue-50/60 border border-blue-100 space-y-4">
                        <div class="flex items-center space-x-2 text-[#0B5CFF]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <h3 class="font-bold text-xs uppercase tracking-wider text-slate-900">Tulis Ulasan Pengalaman Anda</h3>
                        </div>

                        <form action="{{ route('products.reviews.store', $product->slug) }}" method="POST" class="space-y-4" x-data="{ selectedRating: 5 }">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Penilaian Bintang <span class="text-rose-500">*</span></label>
                                <div class="flex items-center space-x-2">
                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                        <button type="button" @click="selectedRating = star" class="p-1 focus:outline-hidden">
                                            <svg class="w-6 h-6 transition" :class="star <= selectedRating ? 'text-amber-400 fill-current' : 'text-slate-300 fill-current'" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    </template>
                                    <span class="text-xs font-bold text-slate-700 ml-2" x-text="selectedRating + ' dari 5 Bintang'"></span>
                                    <input type="hidden" name="rating" :value="selectedRating">
                                </div>
                            </div>

                            <div>
                                <label for="review_title" class="block text-xs font-bold text-slate-700 mb-1">Judul Ulasan (Opsional)</label>
                                <input type="text" name="title" id="review_title" placeholder="Contoh: Performa sangat kencang dan suhu dingin!"
                                    class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                            </div>

                            <div>
                                <label for="review_comment" class="block text-xs font-bold text-slate-700 mb-1">Isi Ulasan <span class="text-rose-500">*</span></label>
                                <textarea name="comment" id="review_comment" rows="3" required placeholder="Ceritakan pengalaman Anda menggunakan perangkat ini, kecepatan pengiriman, dan kemasan paket..."
                                    class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] leading-relaxed"></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    Kirimkan Ulasan
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- REVIEWS LIST -->
                @if($product->reviews->count() > 0)
                    <div class="space-y-4 divide-y divide-slate-100">
                        @foreach($product->reviews as $review)
                            <div class="pt-4 first:pt-0 space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold text-slate-900">{{ $review->user->name ?? 'Pelanggan' }}</span>
                                        @if($review->is_verified_purchase)
                                            <span class="inline-flex items-center text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-bold border border-emerald-200">
                                                ✓ Pembeli Terverifikasi
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ tgl_indo($review->created_at) }}</span>
                                </div>

                                <div class="flex items-center space-x-1 text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    @if($review->title)
                                        <span class="font-bold text-slate-800 ml-1.5">{{ $review->title }}</span>
                                    @endif
                                </div>

                                <p class="text-slate-600 leading-relaxed">{{ $review->comment }}</p>

                                @if($review->admin_reply)
                                    <div class="mt-3 p-3.5 bg-slate-50 border-l-4 border-[#0B5CFF] rounded-r-xl space-y-1">
                                        <div class="flex items-center space-x-1.5 text-[#0B5CFF] font-bold text-[11px]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                            <span>Respon Resmi LEOGATISTORE:</span>
                                        </div>
                                        <p class="text-[11px] text-slate-700 leading-relaxed">{{ $review->admin_reply }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-slate-500 space-y-1">
                        <p class="font-bold text-xs text-slate-700">Belum ada ulasan untuk produk ini</p>
                        <p class="text-[11px] text-slate-400">Jadilah pembeli pertama yang memberikan penilaian!</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- RIGHT: SIMULATION & RELATED INFO -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-gradient-to-br from-blue-50 to-slate-50 p-6 rounded-3xl border border-blue-100 space-y-4">
                <h3 class="text-xs font-black uppercase text-[#0B5CFF] tracking-wider">Perakitan PC Builder</h3>
                <p class="text-xs text-slate-700 leading-relaxed">
                    Ingin menggunakan komponen ini dalam racikan PC kustom Anda? Buka simulator PC Builder untuk memeriksa kompatibilitas soket dan daya listrik secara otomatis.
                </p>
                <a href="{{ route('pc_builder.index') }}" class="block text-center py-2.5 px-4 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs">
                    Buka PC Builder
                </a>
            </div>

            <!-- WARRANTY LOOKUP QUICK ACCESS -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 space-y-3">
                <h3 class="text-xs font-bold text-slate-900 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Pengecekan Serial Garansi
                </h3>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Setelah melakukan pembelian, catat nomor seri yang tertera pada faktur atau bodi unit untuk cek keabsahan garansi kapan saja.
                </p>
                <a href="{{ route('warranty.check') }}" class="text-xs font-bold text-[#0B5CFF] hover:underline flex items-center">
                    Cek Garansi Nomor Seri &rarr;
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
