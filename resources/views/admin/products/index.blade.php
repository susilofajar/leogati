@extends('layouts.admin')

@section('header_title', 'Manajemen Katalog Produk')

@section('content')
<div class="space-y-6">

    <!-- TOP ACTIONS -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Daftar Produk & Varian</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data laptop, komponen PC, harga resmi, stok gudang, dan masa garansi</p>
        </div>

        <a href="{{ route('admin.produk.create') }}" 
            class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-extrabold rounded-xl shadow-xs transition flex items-center shrink-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk Baru
        </a>
    </div>

    <!-- SEARCH & FILTERS -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">
        <form action="{{ route('admin.produk.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            
            <div class="sm:col-span-5">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk / SKU..." 
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
            </div>

            <div class="sm:col-span-3">
                <select name="category_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                    Cari
                </button>
                <a href="{{ route('admin.produk.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- PRODUCTS TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3.5 px-5">Produk & Merek</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">SKU / Varian</th>
                        <th class="py-3.5 px-4">Harga Jual</th>
                        <th class="py-3.5 px-4">Stok</th>
                        <th class="py-3.5 px-4">Garansi</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($products as $product)
                        @php
                            $defaultVariant = $product->defaultVariant;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-900 text-xs line-clamp-1">{{ $product->name }}</div>
                                <div class="text-[11px] text-blue-600 font-semibold">{{ $product->brand->name }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $product->category->name }}</td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-800">
                                {{ $defaultVariant ? $defaultVariant->sku : '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ rupiah($defaultVariant ? $defaultVariant->price : 0) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $product->total_stock > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $product->total_stock }} unit
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $product->warranty_period_months }} Bln</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $product->status === 'active' ? 'bg-blue-100 text-blue-800' : ($product->status === 'draft' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right space-x-2">
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-[#0B5CFF] inline-block" title="Lihat di Storefront">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.produk.edit', $product->id) }}" class="p-1.5 text-slate-400 hover:text-amber-600 inline-block" title="Ubah Produk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('admin.produk.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari katalog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-xs text-slate-500">
                                Belum ada produk dalam katalog atau tidak sesuai filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
