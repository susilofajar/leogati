@extends('layouts.app')

@section('title', 'Daftar Keinginan (Wishlist) - LEOGATISTORE')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- BREADCRUMBS --}}
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-[#0B5CFF]">Akun Saya</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Daftar Keinginan</span>
    </nav>

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                Daftar Keinginan Saya
            </h1>
            <p class="text-xs text-slate-500 mt-1">Simpan produk impian Anda dan pantau ketersediaan serta penawaran terbaiknya.</p>
        </div>
        <div class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl self-start sm:self-auto">
            Total: {{ $wishlists->total() }} Produk
        </div>
    </div>

    {{-- WISHLIST GRID --}}
    @if($wishlists->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wishlists as $item)
                @php
                    $product = $item->product;
                    $variant = $product->defaultVariant;
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-[#0B5CFF] hover:shadow-md transition flex flex-col justify-between group relative">
                    
                    {{-- DELETE BUTTON --}}
                    <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus produk ini dari wishlist?')" 
                            class="w-8 h-8 bg-white/90 hover:bg-rose-50 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-600 shadow-sm border border-slate-200 transition"
                            title="Hapus dari Wishlist">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>

                    <div>
                        {{-- THUMBNAIL --}}
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-44 bg-slate-50 overflow-hidden flex items-center justify-center p-4">
                            <div class="w-16 h-16 rounded-2xl bg-blue-100/70 text-[#0B5CFF] flex items-center justify-center font-black text-xl group-hover:scale-105 transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </a>

                        {{-- DETAILS --}}
                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="px-2 py-0.5 bg-blue-50 text-[#0B5CFF] font-bold rounded-md">
                                    {{ $product->brand->name ?? 'Original' }}
                                </span>
                                <span class="text-slate-400 text-[10px]">
                                    Ditambahkan {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-[#0B5CFF] transition line-clamp-2">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <div class="pt-1">
                                <span class="text-[10px] text-slate-400 font-semibold block">Harga</span>
                                <p class="text-base font-extrabold text-[#0B5CFF]">
                                    {{ rupiah($variant ? $variant->price : 0) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="p-4 pt-0 border-t border-slate-100 mt-2 space-y-2">
                        @if($variant && $variant->stock > 0)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" 
                                    class="w-full py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5 shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    + Keranjang
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full py-2 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed text-center">
                                Stok Habis
                            </button>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" 
                            class="block text-center py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold text-xs rounded-xl transition">
                            Lihat Detail
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="pt-4">
            {{ $wishlists->links() }}
        </div>
    @else
        {{-- EMPTY STATE --}}
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-4 max-w-md mx-auto">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-rose-50 text-rose-400 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Daftar Keinginan Kosong</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Anda belum menyimpan produk favorit apapun ke dalam wishlist.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-[#0B5CFF] text-white text-xs font-bold rounded-xl shadow-xs inline-block hover:bg-[#063B9E] transition">
                    Jelajahi Katalog Produk
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
