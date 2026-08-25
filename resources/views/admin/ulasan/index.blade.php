@extends('layouts.admin')

@section('header_title', 'Moderasi Ulasan & Penilaian Produk')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Ulasan Pembeli</h2>
            <p class="text-xs text-slate-500">Moderasi ulasan pembeli terverifikasi, sensor ulasan tidak pantas, dan berikan tanggapan resmi toko.</p>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.ulasan.index') }}" 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ !request()->has('approved') ? 'bg-[#0B5CFF] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Ulasan
            </a>
            <a href="{{ route('admin.ulasan.index', ['approved' => '1']) }}" 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('approved') === '1' ? 'bg-[#0B5CFF] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Disetujui (Publik)
            </a>
            <a href="{{ route('admin.ulasan.index', ['approved' => '0']) }}" 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('approved') === '0' ? 'bg-[#0B5CFF] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Disembunyikan
            </a>
        </div>

        <form action="{{ route('admin.ulasan.index') }}" method="GET" class="flex items-center space-x-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari isi ulasan, produk, nama..." 
                class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF] w-64">
            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                Cari
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($reviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-4">Produk</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Rating</th>
                            <th class="py-3 px-4">Ulasan</th>
                            <th class="py-3 px-4">Tanggapan Toko</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4 max-w-[180px]">
                                    <p class="font-bold text-slate-800 truncate">{{ $review->product->name ?? 'Produk' }}</p>
                                    @if($review->order)
                                        <span class="text-[10px] text-slate-400 font-mono">Order #{{ $review->order->order_number }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <p class="font-bold text-slate-800">{{ $review->user->name ?? '-' }}</p>
                                    @if($review->is_verified_purchase)
                                        <span class="inline-flex items-center text-[10px] text-emerald-600 font-bold">
                                            ✓ Verified Buyer
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        <span class="ml-1 text-slate-700 font-bold text-xs">{{ $review->rating }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs">
                                    @if($review->title)
                                        <p class="font-bold text-slate-800 text-[11px] mb-0.5">{{ $review->title }}</p>
                                    @endif
                                    <p class="text-slate-600 line-clamp-2">{{ $review->comment }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($review->admin_reply)
                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 inline-flex items-center">
                                            ✓ Dibalas
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-medium">Belum dibalas</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($review->is_approved)
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Publik
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Disembunyikan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-2">
                                    <a href="{{ route('admin.ulasan.show', $review->id) }}" 
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-[#0B5CFF] hover:text-white text-slate-700 font-bold rounded-lg transition inline-block">
                                        Rincian & Balas
                                    </a>
                                    <form action="{{ route('admin.ulasan.toggle', $review->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 {{ $review->is_approved ? 'bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white' }} font-bold rounded-lg transition text-[11px]">
                                            {{ $review->is_approved ? 'Sembunyikan' : 'Tampilkan' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="p-8 text-center text-slate-500">
                <p class="font-bold text-sm text-slate-700">Belum ada ulasan produk</p>
                <p class="text-xs text-slate-400 mt-1">Ulasan dari pelanggan setelah menerima pesanan akan muncul di sini.</p>
            </div>
        @endif
    </div>

</div>
@endsection
