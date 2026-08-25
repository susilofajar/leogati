@extends('layouts.admin')

@section('header_title', 'Manajemen Pesanan Masuk')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Daftar Seluruh Pesanan Transaksi</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola verifikasi pembayaran, proses perakitan, dan input nomor resi pengiriman</p>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">
        <form action="{{ route('admin.pesanan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            
            <div class="sm:col-span-5">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor pesanan / nama pelanggan..." 
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    <option value="">Semua Status Pesanan</option>
                    <option value="awaiting_payment" {{ request('status') == 'awaiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pembayaran Diterima</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="packed" {{ request('status') == 'packed' ? 'selected' : '' }}>Selesai Dikemas</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="payment_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    <option value="">Semua Pembayaran</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Lunas</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                    Filter
                </button>
                <a href="{{ route('admin.pesanan.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- ORDERS TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3.5 px-5">Nomor Pesanan</th>
                        <th class="py-3.5 px-4">Pelanggan</th>
                        <th class="py-3.5 px-4">Total Item</th>
                        <th class="py-3.5 px-4">Total Tagihan</th>
                        <th class="py-3.5 px-4">Metode Bayar</th>
                        <th class="py-3.5 px-4">Status Bayar</th>
                        <th class="py-3.5 px-4">Status Pesanan</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-5 font-mono font-bold text-slate-900 text-xs">
                                <a href="{{ route('admin.pesanan.show', $order->id) }}" class="text-[#0B5CFF] hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <div class="text-[10px] text-slate-400 font-sans font-normal">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $order->user->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $order->user->email }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $order->items->sum('quantity') }} unit
                            </td>
                            <td class="py-3.5 px-4 font-extrabold text-slate-900">
                                {{ rupiah($order->total_amount) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 text-[11px]">
                                {{ $order->payment_method_name }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($order->payment_status === 'unpaid' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'unpaid' ? 'Belum Bayar' : 'Gagal') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->status_details['class'] }}">
                                    {{ $order->status_details['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('admin.pesanan.show', $order->id) }}" 
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-[#0B5CFF] text-slate-700 hover:text-white font-bold text-xs rounded-xl transition inline-block">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-xs text-slate-500">
                                Belum ada pesanan transaksi masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
