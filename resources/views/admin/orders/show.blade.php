@extends('layouts.admin')

@section('header_title', 'Kelola Pesanan: ' . $order->order_number)

@section('content')
<div class="max-w-5xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Kelola Pesanan {{ $order->order_number }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui status pembayaran, progres pengemasan, dan nomor resi pengiriman</p>
        </div>

        <a href="{{ route('admin.pesanan.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Pesanan
        </a>
    </div>

    <!-- UPDATE STATUS FORM -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B5CFF] border-b border-slate-100 pb-2">
            Perbarui Status & Pengiriman
        </h3>

        <form action="{{ route('admin.pesanan.update_status', $order->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            @csrf
            @method('PUT')

            <div class="sm:col-span-4">
                <label for="status" class="block text-xs font-bold text-slate-700 mb-1">Status Pesanan</label>
                <select name="status" id="status" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold focus:outline-hidden focus:border-[#0B5CFF]">
                    <option value="awaiting_payment" {{ $order->status == 'awaiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Pembayaran Diterima</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Sedang Diproses / Dirakit</option>
                    <option value="packed" {{ $order->status == 'packed' ? 'selected' : '' }}>Selesai Dikemas</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Sampai di Tujuan</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <label for="payment_status" class="block text-xs font-bold text-slate-700 mb-1">Status Pembayaran</label>
                <select name="payment_status" id="payment_status" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold focus:outline-hidden focus:border-[#0B5CFF]">
                    <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Sudah Lunas</option>
                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Dikembalikan (Refund)</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <label for="shipping_tracking_number" class="block text-xs font-bold text-slate-700 mb-1">Nomor Resi Ekspedisi</label>
                <input type="text" name="shipping_tracking_number" id="shipping_tracking_number" 
                    value="{{ old('shipping_tracking_number', $order->shipping_tracking_number) }}"
                    placeholder="Contoh: JNE889210034"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono focus:outline-hidden focus:border-[#0B5CFF]">
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="w-full py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- ORDER DETAIL OVERVIEW -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] uppercase font-bold text-slate-400">Informasi Pembeli</span>
            <p class="text-xs font-bold text-slate-900">{{ $order->user->name }}</p>
            <p class="text-[11px] text-slate-500">{{ $order->user->email }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] uppercase font-bold text-slate-400">Metode & Tagihan</span>
            <p class="text-xs font-bold text-slate-900">{{ $order->payment_method_name }}</p>
            <p class="text-sm font-extrabold text-[#0B5CFF]">{{ rupiah($order->total_amount) }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] uppercase font-bold text-slate-400">Ekspedisi Pengiriman</span>
            <p class="text-xs font-bold text-slate-900">{{ $order->courier_name }}</p>
            <p class="text-[11px] font-mono text-blue-600 font-bold">{{ $order->shipping_tracking_number ?? 'Belum ada nomor resi' }}</p>
        </div>

    </div>

    <!-- ITEMS TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Rincian Barang yang Dipesan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="py-3 px-5">Produk & Varian</th>
                        <th class="py-3 px-4">SKU</th>
                        <th class="py-3 px-4">Harga Satuan</th>
                        <th class="py-3 px-4">Jumlah</th>
                        <th class="py-3 px-5 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-900">{{ $item->product_name }}</div>
                                <div class="text-[11px] text-slate-500">Varian: {{ $item->variant_name }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">{{ $item->sku }}</td>
                            <td class="py-3.5 px-4">{{ rupiah($item->unit_price) }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $item->quantity }} unit</td>
                            <td class="py-3.5 px-5 text-right font-extrabold text-slate-900">{{ rupiah($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200 text-xs font-semibold text-slate-700">
                    <tr>
                        <td colspan="4" class="py-2.5 px-5 text-right">Subtotal Produk:</td>
                        <td class="py-2.5 px-5 text-right font-bold">{{ rupiah($order->subtotal_amount) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="py-2 px-5 text-right">Biaya Pengiriman:</td>
                        <td class="py-2 px-5 text-right font-bold">{{ rupiah($order->shipping_amount) }}</td>
                    </tr>
                    <tr class="border-t border-slate-200 font-extrabold text-slate-900 text-sm">
                        <td colspan="4" class="py-3 px-5 text-right">Total Transaksi:</td>
                        <td class="py-3 px-5 text-right text-[#0B5CFF]">{{ rupiah($order->total_amount) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- SHIPPING ADDRESS & NOTES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-2 text-xs">
            <h4 class="font-bold text-slate-900 uppercase tracking-wider text-[10px]">Alamat Tujuan Pengiriman</h4>
            <div class="text-slate-600 space-y-0.5">
                <p class="font-bold text-slate-900">{{ $order->shipping_address['recipient_name'] ?? '-' }}</p>
                <p>{{ $order->shipping_address['phone_number'] ?? '-' }}</p>
                <p>{{ $order->shipping_address['address_line'] ?? '-' }}</p>
                <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-2 text-xs">
            <h4 class="font-bold text-slate-900 uppercase tracking-wider text-[10px]">Catatan Khusus Pembeli</h4>
            <p class="text-slate-600 italic">
                {{ $order->notes ?? 'Tidak ada catatan tambahan dari pembeli.' }}
            </p>
        </div>
    </div>

</div>
@endsection
