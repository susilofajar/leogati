@extends('layouts.app')

@section('title', 'Bandingkan Spesifikasi Produk - LEOGATISTORE')
@section('meta_description', 'Bandingkan performa, spesifikasi teknis hardware, dan harga produk laptop atau komponen komputer secara side-by-side di LEOGATISTORE.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- BREADCRUMBS --}}
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#0B5CFF]">Katalog</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Perbandingan Produk</span>
    </nav>

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#0B5CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Perbandingan Spesifikasi Produk
            </h1>
            <p class="text-xs text-slate-500 mt-1">Bandingkan hingga 4 produk sekaligus untuk menemukan perangkat yang paling tepat untuk kebutuhan Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                + Tambah Produk Lain
            </a>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200 overflow-x-auto shadow-2xs">
            <table class="w-full text-xs text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70">
                        <th class="p-4 w-48 font-bold text-slate-500 uppercase tracking-wider text-[11px] align-top">
                            Produk
                        </th>
                        @foreach($products as $product)
                            @php $variant = $product->defaultVariant; @endphp
                            <th class="p-4 font-normal align-top border-l border-slate-100 w-64 max-w-[280px]">
                                <div class="space-y-3 relative">
                                    {{-- REMOVE BTN --}}
                                    <form action="{{ route('comparison.remove', $product->id) }}" method="POST" class="absolute top-0 right-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 transition" title="Hapus dari perbandingan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- THUMBNAIL --}}
                                    <div class="w-full h-28 bg-slate-100 rounded-xl flex items-center justify-center text-[#0B5CFF]">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <span class="text-[10px] font-bold text-[#0B5CFF] uppercase">{{ $product->brand->name ?? '' }}</span>
                                        <h3 class="font-bold text-slate-900 text-xs line-clamp-2 mt-0.5">
                                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-[#0B5CFF]">
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                    </div>

                                    <div class="pt-1">
                                        <p class="text-base font-extrabold text-[#0B5CFF]">
                                            {{ rupiah($variant ? $variant->price : 0) }}
                                        </p>
                                    </div>

                                    {{-- CTA --}}
                                    @if($variant && $variant->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="w-full py-1.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-[11px] font-bold rounded-lg transition shadow-xs">
                                                + Keranjang
                                            </button>
                                        </form>
                                    @else
                                        <span class="block text-center py-1.5 bg-slate-100 text-slate-400 text-[11px] font-bold rounded-lg">
                                            Stok Habis
                                        </span>
                                    @endif
                                </div>
                            </th>
                        @endforeach

                        {{-- EMPTY SLOTS IF LESS THAN 4 --}}
                        @for($i = $products->count(); $i < 4; $i++)
                            <th class="p-4 align-middle text-center border-l border-slate-100 w-64 bg-slate-50/30">
                                <a href="{{ route('products.index') }}" class="border-2 border-dashed border-slate-200 hover:border-[#0B5CFF] rounded-2xl p-6 flex flex-col items-center justify-center text-slate-400 hover:text-[#0B5CFF] transition group block">
                                    <svg class="w-8 h-8 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span class="text-xs font-bold">+ Tambah Produk</span>
                                </a>
                            </th>
                        @endfor
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    {{-- OVERVIEW SECTION --}}
                    <tr class="bg-slate-50">
                        <td colspan="{{ 1 + max(4, $products->count()) }}" class="p-3 font-bold text-slate-800 uppercase tracking-wider text-[11px]">
                            Ringkasan Umum
                        </td>
                    </tr>
                    <tr>
                        <td class="p-3.5 font-semibold text-slate-500 bg-slate-50/40">Kategori</td>
                        @foreach($products as $product)
                            <td class="p-3.5 border-l border-slate-100 font-medium text-slate-800">{{ $product->category->name ?? '-' }}</td>
                        @endforeach
                        @for($i = $products->count(); $i < 4; $i++) <td class="border-l border-slate-100"></td> @endfor
                    </tr>
                    <tr>
                        <td class="p-3.5 font-semibold text-slate-500 bg-slate-50/40">Garansi Resmi</td>
                        @foreach($products as $product)
                            <td class="p-3.5 border-l border-slate-100 font-medium text-slate-800">{{ $product->warranty_period_months }} Bulan</td>
                        @endforeach
                        @for($i = $products->count(); $i < 4; $i++) <td class="border-l border-slate-100"></td> @endfor
                    </tr>
                    <tr>
                        <td class="p-3.5 font-semibold text-slate-500 bg-slate-50/40">Ketersediaan</td>
                        @foreach($products as $product)
                            @php $variant = $product->defaultVariant; @endphp
                            <td class="p-3.5 border-l border-slate-100 font-bold {{ $variant && $variant->stock > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $variant && $variant->stock > 0 ? 'Tersedia (' . $variant->stock . ' unit)' : 'Stok Habis' }}
                            </td>
                        @endforeach
                        @for($i = $products->count(); $i < 4; $i++) <td class="border-l border-slate-100"></td> @endfor
                    </tr>

                    {{-- SPECIFICATION GROUPS --}}
                    @foreach($specGroups as $groupName => $attributes)
                        <tr class="bg-slate-50">
                            <td colspan="{{ 1 + max(4, $products->count()) }}" class="p-3 font-bold text-slate-800 uppercase tracking-wider text-[11px]">
                                {{ $groupName }}
                            </td>
                        </tr>
                        @foreach($attributes as $attrName)
                            <tr>
                                <td class="p-3.5 font-semibold text-slate-500 bg-slate-50/40">{{ $attrName }}</td>
                                @foreach($products as $product)
                                    @php
                                        $val = '-';
                                        foreach ($product->specifications as $spec) {
                                            if (($spec->attribute->name ?? '') === $attrName) {
                                                $val = $spec->value;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <td class="p-3.5 border-l border-slate-100 font-medium text-slate-800">{{ $val }}</td>
                                @endforeach
                                @for($i = $products->count(); $i < 4; $i++) <td class="border-l border-slate-100"></td> @endfor
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- EMPTY STATE --}}
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-4 max-w-md mx-auto">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Belum Ada Produk yang Dibandingkan</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Pilih produk dari katalog kami untuk melihat perbandingan spesifikasi dan harga secara langsung.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-[#0B5CFF] text-white text-xs font-bold rounded-xl shadow-xs inline-block hover:bg-[#063B9E] transition">
                    Pilih Produk di Katalog
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
