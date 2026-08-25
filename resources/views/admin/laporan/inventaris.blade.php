@extends('layouts.admin')

@section('header_title', 'Laporan Inventaris')

@section('content')
<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">Laporan Inventaris & Stok</h2>
        <p class="text-xs text-slate-500 mt-1">Pantau stok kritis, produk mati, dan riwayat mutasi stok secara lengkap.</p>
    </div>

    {{-- STOK RENDAH / KRITIS --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-rose-100 bg-rose-50/40 flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <h3 class="text-sm font-bold text-rose-800">Stok Kritis (&le; 5 Unit)</h3>
                <p class="text-xs text-rose-600 mt-0.5">{{ $lowStock->count() }} varian produk memerlukan pengisian segera</p>
            </div>
        </div>
        @if($lowStock->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-4 text-left">Produk</th>
                            <th class="py-2.5 px-4 text-left">SKU</th>
                            <th class="py-2.5 px-4 text-left">Kategori</th>
                            <th class="py-2.5 px-4 text-right">Harga Jual</th>
                            <th class="py-2.5 px-4 text-right">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($lowStock as $variant)
                            <tr class="hover:bg-rose-50/30">
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-900">{{ $variant->product?->name }}</p>
                                    <p class="text-slate-500 text-[10px]">{{ $variant->name }}</p>
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-mono">{{ $variant->sku }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $variant->product?->category?->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold text-slate-900">{{ rupiah($variant->price) }}</td>
                                <td class="py-3 px-4 text-right">
                                    <span @class([
                                        'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black',
                                        'bg-rose-100 text-rose-700' => $variant->stock === 0,
                                        'bg-amber-100 text-amber-700' => $variant->stock > 0 && $variant->stock <= 3,
                                        'bg-orange-100 text-orange-700' => $variant->stock > 3,
                                    ])>{{ $variant->stock }} unit</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center py-10">
                <svg class="w-10 h-10 text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm font-bold text-slate-700">Semua stok aman!</p>
                <p class="text-xs text-slate-500 mt-1">Tidak ada produk dengan stok di bawah batas kritis.</p>
            </div>
        @endif
    </div>

    {{-- DEAD STOCK --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Produk Mati <span class="text-slate-400 font-normal">(Tidak Terjual 90 Hari)</span></h3>
            <p class="text-xs text-slate-500 mt-0.5">{{ $deadStock->count() }} varian memiliki stok tetapi tidak terjual dalam 90 hari terakhir</p>
        </div>
        @if($deadStock->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-4 text-left">Produk / Varian</th>
                            <th class="py-2.5 px-4 text-left">SKU</th>
                            <th class="py-2.5 px-4 text-left">Merek</th>
                            <th class="py-2.5 px-4 text-right">Stok</th>
                            <th class="py-2.5 px-4 text-right">Nilai Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($deadStock->take(20) as $variant)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-900">{{ $variant->product?->name }}</p>
                                    <p class="text-slate-500 text-[10px]">{{ $variant->name }}</p>
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-mono">{{ $variant->sku }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $variant->product?->brand?->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold text-slate-900">{{ number_format($variant->stock) }}</td>
                                <td class="py-3 px-4 text-right text-slate-600">{{ rupiah($variant->stock * $variant->price) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center py-8 text-xs text-slate-400">Tidak ada dead stock — semua produk bergerak!</p>
        @endif
    </div>

    {{-- FILTER RIWAYAT MUTASI --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Riwayat Mutasi Stok</h3>
        </div>
        <div class="p-5 border-b border-slate-100">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Tipe Mutasi</label>
                    <select name="type" class="border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                        <option value="">Semua Tipe</option>
                        @foreach(['purchase','sale','return','adjustment','transfer','damage','reservation','release'] as $t)
                            <option value="{{ $t }}" @selected(($movementFilters['type'] ?? '') === $t)>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Gudang</label>
                    <select name="warehouse_id" class="border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                        <option value="">Semua Gudang</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" @selected(($movementFilters['warehouse_id'] ?? '') == $wh->id)>{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Dari</label>
                    <input type="date" name="from" value="{{ $movementFilters['from'] ?? '' }}"
                        class="border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Sampai</label>
                    <input type="date" name="to" value="{{ $movementFilters['to'] ?? '' }}"
                        class="border border-slate-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0B5CFF]/30">
                </div>
                <button type="submit" class="px-5 py-2 bg-[#0B5CFF] text-white rounded-xl text-sm font-bold hover:bg-[#063B9E] transition">Filter</button>
                <a href="{{ route('admin.laporan.inventaris') }}" class="px-5 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition">Reset</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-2.5 px-4 text-left">Waktu</th>
                        <th class="py-2.5 px-4 text-left">Produk / SKU</th>
                        <th class="py-2.5 px-4 text-left">Tipe</th>
                        <th class="py-2.5 px-4 text-right">Perubahan</th>
                        <th class="py-2.5 px-4 text-right">Stok Akhir</th>
                        <th class="py-2.5 px-4 text-left">Catatan</th>
                        <th class="py-2.5 px-4 text-left">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($movements as $mov)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-500">{{ tgl_indo($mov->created_at) }}</td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-slate-900 truncate max-w-[140px]">{{ $mov->productVariant?->product?->name }}</p>
                                <p class="text-[10px] text-slate-500">{{ $mov->productVariant?->sku }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <span @class([
                                    'px-2 py-0.5 rounded-full text-[10px] font-bold',
                                    'bg-emerald-100 text-emerald-700' => in_array($mov->type, ['purchase','return','release']),
                                    'bg-rose-100 text-rose-700'       => in_array($mov->type, ['sale','damage']),
                                    'bg-amber-100 text-amber-700'     => in_array($mov->type, ['adjustment','transfer','reservation']),
                                ])>{{ ucfirst($mov->type) }}</span>
                            </td>
                            <td class="py-3 px-4 text-right font-black {{ $mov->quantity_change > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $mov->quantity_change > 0 ? '+' : '' }}{{ number_format($mov->quantity_change) }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">{{ number_format($mov->quantity_after) }}</td>
                            <td class="py-3 px-4 text-slate-600 max-w-[150px] truncate">{{ $mov->notes ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $mov->user?->name ?? 'Sistem' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-8 text-slate-400">Tidak ada riwayat mutasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($movements->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $movements->links() }}</div>
        @endif
    </div>

</div>
@endsection
