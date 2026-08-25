@extends('layouts.app')

@section('title', 'Katalog Perangkat Komputer, Laptop & Komponen PC')
@section('meta_description', 'Jelajahi pilihan laptop gaming, prosesor, motherboard, kartu grafis, dan komponen PC bergaransi resmi terbaik di LEOGATISTORE.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Katalog Produk</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- SIDEBAR FILTERS -->
        <aside class="w-full lg:w-64 shrink-0 space-y-6">
            <form action="{{ route('products.index') }}" method="GET" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Filter Produk</h3>
                    <a href="{{ route('products.index') }}" class="text-[11px] text-[#0B5CFF] font-semibold hover:underline">Atur Ulang</a>
                </div>

                <!-- KEYWORD SEARCH -->
                <div>
                    <label for="q" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Kunci</label>
                    <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Cari nama / SKU / merek..."
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                </div>

                <!-- CATEGORY FILTER -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Kategori</label>
                    <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                        <label class="flex items-center space-x-2 text-slate-600 hover:text-slate-900 cursor-pointer">
                            <input type="radio" name="kategori" value="" {{ !request('kategori') ? 'checked' : '' }} class="text-[#0B5CFF] focus:ring-[#0B5CFF]">
                            <span>Semua Kategori</span>
                        </label>
                        @foreach($categories as $cat)
                            <label class="flex items-center space-x-2 text-slate-600 hover:text-slate-900 cursor-pointer">
                                <input type="radio" name="kategori" value="{{ $cat->slug }}" {{ request('kategori') == $cat->slug ? 'checked' : '' }} class="text-[#0B5CFF] focus:ring-[#0B5CFF]">
                                <span>{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- BRAND FILTER -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Merek Resmi</label>
                    <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                        <label class="flex items-center space-x-2 text-slate-600 hover:text-slate-900 cursor-pointer">
                            <input type="radio" name="merek" value="" {{ !request('merek') ? 'checked' : '' }} class="text-[#0B5CFF] focus:ring-[#0B5CFF]">
                            <span>Semua Merek</span>
                        </label>
                        @foreach($brands as $b)
                            <label class="flex items-center space-x-2 text-slate-600 hover:text-slate-900 cursor-pointer">
                                <input type="radio" name="merek" value="{{ $b->slug }}" {{ request('merek') == $b->slug ? 'checked' : '' }} class="text-[#0B5CFF] focus:ring-[#0B5CFF]">
                                <span>{{ $b->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- PRICE RANGE FILTER -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Rentang Harga (Rp)</label>
                    <div class="space-y-2">
                        <input type="number" name="harga_min" value="{{ request('harga_min') }}" placeholder="Harga Minimum"
                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="Harga Maksimum"
                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    </div>
                </div>

                <!-- STOCK ONLY FILTER -->
                <div>
                    <label class="flex items-center space-x-2 text-xs text-slate-700 font-semibold cursor-pointer">
                        <input type="checkbox" name="stok_tersedia" value="1" {{ request('stok_tersedia') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-300 text-[#0B5CFF] focus:ring-[#0B5CFF]">
                        <span>Hanya Stok Tersedia</span>
                    </label>
                </div>

                <input type="hidden" name="urutan" value="{{ request('urutan', 'terbaru') }}">

                <!-- SUBMIT BUTTON -->
                <button type="submit" class="w-full py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Terapkan Filter
                </button>
            </form>
        </aside>

        <!-- MAIN PRODUCTS GRID -->
        <main class="flex-1 space-y-6">
            
            <!-- HEADER TOOLBAR -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-base font-extrabold text-slate-900">
                        Katalog Produk Teknologi
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">Menampilkan <strong>{{ $products->total() }}</strong> produk resmi</p>
                </div>

                <!-- SORT DROPDOWN -->
                <div class="flex items-center space-x-2 text-xs w-full sm:w-auto">
                    <span class="text-slate-500 shrink-0 font-medium">Urutkan:</span>
                    <form action="{{ route('products.index') }}" method="GET" class="w-full sm:w-auto">
                        @foreach(request()->except('urutan', 'page') as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <select name="urutan" onchange="this.form.submit()" 
                            class="w-full sm:w-auto px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-[#0B5CFF]">
                            <option value="terbaru" {{ request('urutan') == 'terbaru' ? 'selected' : '' }}>Produk Terbaru</option>
                            <option value="termurah" {{ request('urutan') == 'termurah' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                            <option value="termahal" {{ request('urutan') == 'termahal' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                            <option value="nama_az" {{ request('urutan') == 'nama_az' ? 'selected' : '' }}>Nama: A ke Z</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- PRODUCT GRID -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($products as $product)
                        @php
                            $defaultVariant = $product->defaultVariant;
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200/80 hover:border-[#0B5CFF] hover:shadow-md transition flex flex-col justify-between overflow-hidden group">
                            
                            <div class="p-4 space-y-3">
                                <!-- BRAND & WARRANTY BADGES -->
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="px-2 py-0.5 bg-blue-50 text-[#0B5CFF] font-bold rounded-md">
                                        {{ $product->brand->name }}
                                    </span>
                                    <span class="text-slate-500 font-medium flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        Garansi {{ $product->warranty_period_months }} Bln
                                    </span>
                                </div>

                                <!-- IMAGE PLACEHOLDER OR THUMBNAIL -->
                                <div class="relative w-full h-40 bg-slate-50 rounded-xl overflow-hidden flex items-center justify-center p-4">
                                    <a href="{{ route('products.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                                        <div class="w-16 h-16 rounded-2xl bg-blue-100/70 text-[#0B5CFF] flex items-center justify-center font-black text-xl group-hover:scale-105 transition">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                    </a>

                                    @if($product->is_featured)
                                        <span class="absolute top-2 left-2 px-2 py-0.5 bg-amber-400 text-slate-900 text-[10px] font-extrabold rounded-md shadow-xs pointer-events-none">
                                            UNGGULAN
                                        </span>
                                    @endif

                                    <!-- WISHLIST & COMPARE ACTIONS -->
                                    <div class="absolute top-2 right-2 flex flex-col gap-1.5 z-10">
                                        @auth
                                            @php
                                                $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                                            @endphp
                                            <form action="{{ route('wishlist.toggle') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="w-7 h-7 rounded-full bg-white/90 hover:bg-white flex items-center justify-center shadow-xs border border-slate-200 transition {{ $inWishlist ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500' }}" title="{{ $inWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}">
                                                    <svg class="w-4 h-4" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="w-7 h-7 rounded-full bg-white/90 hover:bg-white flex items-center justify-center shadow-xs border border-slate-200 text-slate-400 hover:text-rose-500 transition" title="Masuk untuk menambah Wishlist">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                            </a>
                                        @endauth

                                        <form action="{{ route('comparison.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="w-7 h-7 rounded-full bg-white/90 hover:bg-white flex items-center justify-center shadow-xs border border-slate-200 text-slate-400 hover:text-[#0B5CFF] transition" title="Bandingkan Produk">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- PRODUCT INFO -->
                                <div>
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $product->category->name }}</p>
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-[#0B5CFF] transition line-clamp-2 mt-0.5">
                                        <a href="{{ route('products.show', $product->slug) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $product->short_description }}</p>
                                </div>
                            </div>

                            <!-- FOOTER / PRICE & CTA -->
                            <div class="p-4 pt-0 border-t border-slate-50 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold block">Mulai Dari</span>
                                    <p class="text-sm sm:text-base font-extrabold text-[#0B5CFF]">
                                        {{ rupiah($defaultVariant ? $defaultVariant->price : 0) }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-[#0B5CFF] text-slate-700 hover:text-white font-bold text-xs rounded-xl transition">
                                        Detail
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- PAGINATION -->
                <div class="pt-4">
                    {{ $products->links() }}
                </div>
            @else
                <!-- EMPTY STATE -->
                <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Tidak Ada Produk yang Cocok</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        Coba sesuaikan filter kategori, merek, atau rentang harga pencarian Anda.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-[#0B5CFF] text-white text-xs font-bold rounded-xl shadow-xs inline-block">
                            Lihat Semua Produk
                        </a>
                    </div>
                </div>
            @endif

        </main>

    </div>

</div>
@endsection
