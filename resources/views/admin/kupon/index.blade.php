@extends('layouts.admin')

@section('header_title', 'Manajemen Kupon & Promosi')

@section('content')
<div class="space-y-6">

    <!-- HEADER & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Kupon Promo</h2>
            <p class="text-xs text-slate-500">Kelola voucher diskon belanja, potongan persentase, nominal tetap, dan batas kuota pemakaian.</p>
        </div>
        <a href="{{ route('admin.kupon.create') }}" 
            class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center shrink-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Kupon Baru
        </a>
    </div>

    <!-- SEARCH BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <form action="{{ route('admin.kupon.index') }}" method="GET" class="flex items-center space-x-2 w-full max-w-md">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode kupon atau nama promo..." 
                class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                Cari
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($coupons->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-4">Kode Kupon</th>
                            <th class="py-3 px-4">Nama Promo</th>
                            <th class="py-3 px-4">Besaran Diskon</th>
                            <th class="py-3 px-4">Min. Belanja</th>
                            <th class="py-3 px-4">Pemakaian</th>
                            <th class="py-3 px-4">Periode Berlaku</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($coupons as $coupon)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">
                                    {{ $coupon->name }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-700">
                                    @if($coupon->type === 'percent')
                                        <span class="font-bold text-emerald-600">{{ $coupon->value }}%</span>
                                        @if($coupon->max_discount_amount)
                                            <span class="text-[10px] text-slate-400 block">(Maks: {{ rupiah($coupon->max_discount_amount) }})</span>
                                        @endif
                                    @else
                                        <span class="font-bold text-emerald-600">{{ rupiah($coupon->value) }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $coupon->min_purchase_amount > 0 ? rupiah($coupon->min_purchase_amount) : 'Tanpa Min.' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-800">{{ $coupon->used_count }}</span>
                                    <span class="text-slate-400">/ {{ $coupon->usage_limit ? $coupon->usage_limit . ' kali' : '∞' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                    @if($coupon->start_date && $coupon->end_date)
                                        {{ tgl_indo($coupon->start_date) }} &ndash; {{ tgl_indo($coupon->end_date) }}
                                    @elseif($coupon->end_date)
                                        Hingga {{ tgl_indo($coupon->end_date) }}
                                    @else
                                        Selamanya
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($coupon->is_active && $coupon->isValid())
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            Nonaktif / Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2">
                                    <a href="{{ route('admin.kupon.edit', $coupon->id) }}" 
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-[#0B5CFF] hover:text-white text-slate-700 font-bold rounded-lg transition inline-block">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.kupon.destroy', $coupon->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kupon promo ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 font-bold rounded-lg transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $coupons->links() }}
            </div>
        @else
            <div class="p-8 text-center text-slate-500">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                <p class="font-bold text-sm text-slate-700">Belum ada kupon promosi</p>
                <p class="text-xs text-slate-400 mt-1">Buat kode kupon diskon pertama untuk meningkatkan transaksi toko.</p>
            </div>
        @endif
    </div>

</div>
@endsection
