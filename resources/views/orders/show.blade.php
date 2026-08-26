@extends('layouts.app')

@section('title', 'Rincian Pesanan ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('customer.orders.index') }}" class="hover:text-[#0B5CFF]">Pesanan Saya</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold font-mono">{{ $order->order_number }}</span>
    </nav>

    <!-- HEADER & STATUS -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
            <div>
                <span class="text-xs text-slate-400 font-semibold block">Nomor Pesanan Resmi</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 font-mono mt-0.5">
                    {{ $order->order_number }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Dibuat pada {{ tgl_indo($order->created_at) }} WIB</p>
            </div>

            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-black {{ $order->status_details['class'] }}">
                    {{ $order->status_details['label'] }}
                </span>
            </div>
        </div>

        <!-- PAYMENT INSTRUCTION BOX -->
        @if($order->status === 'awaiting_payment' || $order->payment_status === 'unpaid')
            <div class="bg-gradient-to-br from-blue-900 to-[#071A3D] text-white p-6 rounded-2xl shadow-md space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-extrabold text-blue-300 uppercase tracking-wider">Instruksi Pembayaran</span>
                        <h3 class="text-lg font-black text-white mt-0.5">{{ $order->payment_method_name }}</h3>
                    </div>
                    <span class="px-3 py-1 bg-amber-400 text-slate-950 font-black text-xs rounded-lg shadow-xs">
                        BELUM DIBAYAR
                    </span>
                </div>

                <div class="p-4 bg-white/10 rounded-xl space-y-2 border border-white/10">
                    <div class="flex justify-between items-center text-xs text-blue-200">
                        <span>Total Tagihan:</span>
                        <span class="text-xl font-black text-amber-300">{{ rupiah($order->total_amount) }}</span>
                    </div>

                    @if(in_array($order->payment_method, ['bca_va', 'mandiri_va', 'bri_va', 'bni_va']))
                        <div class="pt-2 border-t border-white/10 flex justify-between items-center text-xs">
                            <span class="text-blue-200">Nomor Virtual Account:</span>
                            <span class="font-mono font-black text-sm text-white tracking-widest bg-white/15 px-3 py-1 rounded-lg">
                                8808{{ rand(10000000, 99999999) }}
                            </span>
                        </div>
                    @elseif($order->payment_method === 'qris')
                        <div class="pt-2 border-t border-white/10 text-center space-y-2">
                            <p class="text-xs text-blue-200">Pindai Kode QRIS Resmi LEOGATISTORE melalui aplikasi e-Wallet Anda:</p>
                            <div class="w-32 h-32 bg-white rounded-xl mx-auto flex items-center justify-center text-slate-900 font-bold text-xs p-2">
                                [QRIS CODE SIMULATION]
                            </div>
                        </div>
                    @else
                        <div class="pt-2 border-t border-white/10 text-xs text-blue-200 space-y-1">
                            <p>Silakan transfer tepat sesuai total tagihan ke rekening resmi:</p>
                            <p class="font-mono font-bold text-white text-sm">BCA: 123-456-7890 a/n PT LEOGATISTORE INDONESIA</p>
                        </div>
                    @endif
                </div>

                <p class="text-[11px] text-blue-200 leading-relaxed">
                    Sistem verifikasi otomatis akan memeriksa pembayaran Anda dalam waktu 1-5 menit setelah transfer berhasil dilakukan.
                </p>
            </div>
        @else
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-2xl flex items-center space-x-3">
                <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h4 class="text-xs font-bold">Pembayaran Berhasil Diterima</h4>
                    <p class="text-[11px] text-emerald-700 mt-0.5">
                        Pembayaran sebesar {{ rupiah($order->total_amount) }} telah terverifikasi pada {{ tgl_indo($order->paid_at ?? $order->updated_at) }}.
                    </p>
                </div>
            </div>
        @endif

    </div>

    <!-- ORDER ITEMS TABLE -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-4">
        <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">
            Rincian Produk & Jaminan Garansi
        </h2>

        <div class="divide-y divide-slate-100 text-xs">
            @foreach($order->items as $item)
                @php
                    $product = $item->variant ? $item->variant->product : null;
                @endphp
                <div class="py-3.5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center space-x-3.5">
                        @if($product)
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-center shrink-0 p-1 overflow-hidden">
                                {!! $product->renderThumbnail('max-h-full max-w-full object-contain', 'w-5 h-5') !!}
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        @endif
                        <div class="space-y-1">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">{{ $item->product_name }}</h3>
                            <p class="text-[11px] text-slate-500">
                                Varian: <strong class="text-slate-800">{{ $item->variant_name }}</strong>
                                <span class="text-slate-300 mx-1">•</span>
                                SKU: <span class="font-mono text-slate-600">{{ $item->sku }}</span>
                            </p>
                            <p class="text-[11px] text-emerald-600 font-semibold flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Garansi Resmi Terdaftar
                            </p>
                        </div>
                    </div>

                    <div class="text-right sm:min-w-[140px]">
                        <p class="text-slate-500 text-[11px]">{{ $item->quantity }} x {{ rupiah($item->unit_price) }}</p>
                        <p class="text-sm font-extrabold text-slate-900">{{ rupiah($item->subtotal) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- TOTALS BREAKDOWN -->
        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal Produk</span>
                <span class="font-bold text-slate-900">{{ rupiah($order->subtotal_amount) }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Biaya Pengiriman ({{ $order->courier_name }})</span>
                <span class="font-bold text-slate-900">{{ rupiah($order->shipping_amount) }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="flex justify-between text-emerald-600 font-semibold">
                    <span>Diskon Promosi</span>
                    <span>-{{ rupiah($order->discount_amount) }}</span>
                </div>
            @endif
            <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                <span class="text-sm font-bold text-slate-900">Total Pembayaran</span>
                <span class="text-xl font-black text-[#0B5CFF]">{{ rupiah($order->total_amount) }}</span>
            </div>
        </div>
    </div>

    <!-- SHIPPING ADDRESS & TRACKING -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-3">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                Informasi Penerima & Alamat
            </h3>
            <div class="text-xs text-slate-600 space-y-1">
                <p class="font-bold text-slate-900">{{ $order->shipping_address['recipient_name'] ?? '-' }}</p>
                <p>{{ $order->shipping_address['phone_number'] ?? '-' }}</p>
                <p>{{ $order->shipping_address['address_line'] ?? '-' }}</p>
                <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-3">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                Ekspedisi & Status Pengiriman
            </h3>
            <div class="text-xs text-slate-600 space-y-2">
                <div>
                    <span class="text-slate-400 block text-[11px]">Jasa Ekspedisi:</span>
                    <span class="font-bold text-slate-800">{{ $order->courier_name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Nomor Resi Pelacakan:</span>
                    @if($order->shipping_tracking_number)
                        <span class="font-mono font-extrabold text-blue-600 text-sm bg-blue-50 px-2 py-0.5 rounded-md inline-block mt-0.5">
                            {{ $order->shipping_tracking_number }}
                        </span>
                    @else
                        <span class="text-slate-400 italic">Nomor resi akan muncul setelah paket dikirim</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="text-center pt-2">
        <a href="{{ route('customer.orders.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition inline-block">
            &larr; Kembali ke Riwayat Pesanan Saya
        </a>
    </div>

</div>
@endsection
