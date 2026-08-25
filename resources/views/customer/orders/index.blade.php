@extends('layouts.app')

@section('title', 'Riwayat Pesanan Belanja Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-[#0B5CFF]">Dashboard Pelanggan</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Pesanan Saya</span>
    </nav>

    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Riwayat Pesanan Belanja</h1>
        <p class="text-xs text-slate-500 mt-0.5">Lacak status pesanan, pembayaran, nomor resi pengiriman, dan klaim garansi produk</p>
    </div>

    <!-- STATUS FILTERS -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 text-xs font-bold whitespace-nowrap">
        <a href="{{ route('customer.orders.index') }}" 
            class="px-4 py-2 rounded-xl transition {{ !request('status') ? 'bg-[#0B5CFF] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'awaiting_payment']) }}" 
            class="px-4 py-2 rounded-xl transition {{ request('status') == 'awaiting_payment' ? 'bg-[#0B5CFF] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Menunggu Pembayaran
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'processing']) }}" 
            class="px-4 py-2 rounded-xl transition {{ request('status') == 'processing' ? 'bg-[#0B5CFF] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Sedang Diproses
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'shipped']) }}" 
            class="px-4 py-2 rounded-xl transition {{ request('status') == 'shipped' ? 'bg-[#0B5CFF] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Sedang Dikirim
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'completed']) }}" 
            class="px-4 py-2 rounded-xl transition {{ request('status') == 'completed' ? 'bg-[#0B5CFF] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Selesai
        </a>
    </div>

    <!-- ORDERS LIST -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4 hover:border-slate-300 transition">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-100 pb-3 text-xs">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono font-black text-slate-900">{{ $order->order_number }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-500">{{ tgl_indo($order->created_at) }}</span>
                        </div>

                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-black {{ $order->status_details['class'] }}">
                            {{ $order->status_details['label'] }}
                        </span>
                    </div>

                    <!-- ITEMS PREVIEW -->
                    <div class="space-y-2 text-xs">
                        @foreach($order->items->take(2) as $item)
                            <div class="flex justify-between items-center text-slate-700">
                                <span class="truncate max-w-md font-medium">{{ $item->product_name }} ({{ $item->variant_name }})</span>
                                <span class="text-slate-500 shrink-0">{{ $item->quantity }}x</span>
                            </div>
                        @endforeach
                        @if($order->items->count() > 2)
                            <p class="text-[11px] text-slate-400 italic">+ {{ $order->items->count() - 2 }} produk lainnya</p>
                        @endif
                    </div>

                    <!-- TOTAL & ACTION -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pt-3 border-t border-slate-100">
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Total Pembayaran</span>
                            <span class="text-base font-extrabold text-[#0B5CFF]">{{ rupiah($order->total_amount) }}</span>
                        </div>

                        <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                            class="px-4 py-2 bg-slate-900 hover:bg-[#0B5CFF] text-white text-xs font-bold rounded-xl transition shadow-xs">
                            Lihat Rincian & Instruksi
                        </a>
                    </div>

                </div>
            @endforeach

            <!-- PAGINATION -->
            <div class="pt-2">
                {{ $orders->links() }}
            </div>
        </div>
    @else
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-sm font-bold text-slate-800">Belum Ada Riwayat Pesanan</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                Anda belum melakukan transaksi pemesanan perangkat di LEOGATISTORE.
            </p>
            <div class="pt-2">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-[#0B5CFF] text-white text-xs font-bold rounded-xl shadow-xs inline-block">
                    Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
