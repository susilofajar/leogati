@extends('layouts.app')

@section('title', 'Keranjang Belanja Perangkat & Komponen')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Keranjang Belanja</span>
    </nav>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Keranjang Belanja</h1>
            <p class="text-xs text-slate-500 mt-0.5">Periksa kembali daftar komponen dan perangkat teknologi sebelum melakukan pembayaran</p>
        </div>

        @if($cart->items->count() > 0)
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 transition">
                    Kosongkan Keranjang
                </button>
            </form>
        @endif
    </div>

    @if($cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- CART ITEMS LIST -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="divide-y divide-slate-100">
                        @foreach($cart->items as $item)
                            @php
                                $variant = $item->variant;
                                $product = $variant ? $variant->product : null;
                            @endphp
                            @if($product)
                                <div class="p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-slate-50/50 transition">
                                    
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('products.show', $product->slug) }}" class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-200/80 text-[#0B5CFF] flex items-center justify-center shrink-0 p-1.5 overflow-hidden hover:border-[#0B5CFF] transition">
                                            {!! $product->renderThumbnail('max-h-full max-w-full object-contain', 'w-7 h-7') !!}
                                        </a>

                                        <div class="space-y-1">
                                            <span class="text-[10px] font-bold uppercase text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                                                {{ $product->brand->name }}
                                            </span>
                                            <h3 class="text-xs sm:text-sm font-bold text-slate-900 line-clamp-1">
                                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-[#0B5CFF]">
                                                    {{ $product->name }}
                                                </a>
                                            </h3>
                                            <p class="text-xs text-slate-500 font-medium">
                                                Varian: <strong class="text-slate-800">{{ $variant->name }}</strong>
                                                <span class="text-slate-300 mx-1">•</span>
                                                SKU: <span class="font-mono text-slate-600">{{ $variant->sku }}</span>
                                            </p>
                                            <p class="text-xs font-bold text-[#0B5CFF] sm:hidden">
                                                {{ rupiah($variant->price) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- QUANTITY & SUBTOTAL & DELETE -->
                                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                        
                                        <!-- QUANTITY CONTROLS -->
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-white">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" 
                                                class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-xs"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                -
                                            </button>
                                            <span class="w-10 text-center text-xs font-bold py-1">
                                                {{ $item->quantity }}
                                            </span>
                                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" 
                                                class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-xs"
                                                {{ $item->quantity >= $variant->stock ? 'disabled' : '' }}>
                                                +
                                            </button>
                                        </form>

                                        <!-- ITEM SUBTOTAL -->
                                        <div class="text-right hidden sm:block min-w-[120px]">
                                            <span class="text-[10px] text-slate-400 font-semibold block">Subtotal</span>
                                            <span class="text-sm font-extrabold text-slate-900">
                                                {{ rupiah($item->subtotal) }}
                                            </span>
                                        </div>

                                        <!-- REMOVE BUTTON -->
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition" title="Hapus Produk">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>

                                    </div>

                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ORDER SUMMARY -->
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3">
                        Ringkasan Belanja
                    </h3>

                    <!-- COUPON PROMO SECTION -->
                    <div class="pt-1">
                        @if($appliedCoupon)
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <div class="flex items-center space-x-1.5">
                                        <span class="font-mono font-bold text-xs text-emerald-800">{{ $appliedCoupon->code }}</span>
                                        <span class="text-[10px] bg-emerald-200 text-emerald-900 font-bold px-1.5 py-0.2 rounded">Teraplikasi</span>
                                    </div>
                                    <p class="text-[11px] text-emerald-700 font-medium">{{ $appliedCoupon->type_label }}</p>
                                </div>
                                <form action="{{ route('cart.remove_coupon') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold p-1">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('cart.apply_coupon') }}" method="POST" class="space-y-2">
                                @csrf
                                <label for="coupon_code" class="text-[11px] font-bold text-slate-700 block">Punya Kupon Diskon Promo?</label>
                                <div class="flex space-x-2">
                                    <input type="text" name="coupon_code" id="coupon_code" placeholder="KODE KUPON" required
                                        class="flex-1 px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono uppercase focus:outline-hidden focus:border-[#0B5CFF]">
                                    <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                                        Gunakan
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                    <div class="space-y-2.5 text-xs pt-2 border-t border-slate-100">
                        <div class="flex justify-between text-slate-600">
                            <span>Total Jumlah Barang</span>
                            <span class="font-bold text-slate-900">{{ $cart->total_quantity }} unit</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Estimasi Berat</span>
                            <span class="font-bold text-slate-900">{{ number_format($cart->total_weight / 1000, 1) }} kg</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal Barang</span>
                            <span class="font-bold text-slate-900">{{ rupiah($cart->subtotal) }}</span>
                        </div>
                        @if($discountAmount > 0)
                            <div class="flex justify-between text-emerald-600 font-bold">
                                <span>Potongan Kupon ({{ $appliedCoupon->code }})</span>
                                <span>- {{ rupiah($discountAmount) }}</span>
                            </div>
                        @endif
                        
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-slate-900">Total Belanja</span>
                            <span class="text-xl font-black text-[#0B5CFF]">
                                {{ rupiah(max(0, $cart->subtotal - $discountAmount)) }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" 
                        class="w-full py-3.5 px-4 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center space-x-2">
                        <span>Lanjut ke Pembayaran</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>

                    <div class="text-center">
                        <a href="{{ route('products.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#0B5CFF] transition">
                            &larr; Tambah Produk Lainnya
                        </a>
                    </div>
                </div>

                <!-- TRUST BADGE -->
                <div class="bg-blue-50/60 p-4 rounded-2xl border border-blue-100 text-xs text-slate-600 space-y-1">
                    <p class="font-bold text-slate-900 flex items-center">
                        <svg class="w-4 h-4 text-[#0B5CFF] mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Proteksi Pembelian & Garansi
                    </p>
                    <p class="text-[11px] text-slate-500">
                        Setiap transaksi dilindungi sistem nomor seri terverifikasi untuk kemudahan klaim garansi distributor resmi.
                    </p>
                </div>
            </div>

        </div>
    @else
        <!-- EMPTY CART STATE -->
        <div class="bg-white p-16 rounded-3xl border border-slate-200 text-center space-y-4 max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h2 class="text-base font-extrabold text-slate-900">Keranjang Belanja Anda Kosong</h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                Anda belum menambahkan komponen atau perangkat komputer ke dalam keranjang belanja.
            </p>
            <div class="pt-2">
                <a href="{{ route('products.index') }}" class="px-6 py-3 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-md transition inline-block">
                    Jelajahi Katalog Produk
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
