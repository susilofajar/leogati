@extends('layouts.admin')

@section('header_title', 'Detail & Tanggapan Ulasan Produk')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- TOP NAV -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.ulasan.index') }}" class="text-xs text-slate-500 hover:text-blue-600 font-bold flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Ulasan
        </a>

        <form action="{{ route('admin.ulasan.toggle', $review->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-3 py-1.5 {{ $review->is_approved ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} text-xs font-bold rounded-lg transition border">
                Status: {{ $review->is_approved ? 'Publik (Klik untuk Sembunyikan)' : 'Disembunyikan (Klik untuk Tampilkan)' }}
            </button>
        </form>
    </div>

    <!-- MAIN GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- REVIEW & OFFICIAL REPLY (2 COLS) -->
        <div class="md:col-span-2 space-y-6">

            <!-- REVIEW CARD -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center space-x-2">
                        <div class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-xs font-bold text-slate-800">{{ $review->rating }} dari 5 Bintang</span>
                    </div>
                    <span class="text-[11px] text-slate-400">{{ tgl_indo($review->created_at) }}</span>
                </div>

                @if($review->title)
                    <h3 class="font-bold text-sm text-slate-900">{{ $review->title }}</h3>
                @endif

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $review->comment }}
                </div>
            </div>

            <!-- OFFICIAL REPLY FORM CARD -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <h3 class="font-bold text-sm text-slate-800 border-b border-slate-100 pb-3">
                    Tanggapan Resmi Toko (Official Seller Response)
                </h3>

                <form action="{{ route('admin.ulasan.reply', $review->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="admin_reply" class="block text-xs font-bold text-slate-700 mb-1">
                            Tulis Tanggapan Resmi untuk Pembeli
                        </label>
                        <textarea name="admin_reply" id="admin_reply" rows="4" required
                            placeholder="Contoh: Terima kasih atas ulasan dan kepercayaannya berbelanja di LEOGATISTORE! Semoga perangkat barunya awet dan membantu produktivitas Anda..."
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF] leading-relaxed @error('admin_reply') border-rose-500 @enderror">{{ old('admin_reply', $review->admin_reply) }}</textarea>
                        @error('admin_reply')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                        @if($review->admin_replied_at)
                            <p class="text-[11px] text-slate-400 mt-1">Terakhir dibalas pada: {{ tgl_indo($review->admin_replied_at) }}</p>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-lg shadow-xs transition">
                            Simpan Tanggapan Resmi
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- SIDEBAR PRODUCT & BUYER INFO (1 COL) -->
        <div class="space-y-6">

            <!-- PRODUCT INFO -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3 text-xs">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400">Produk yang Diulas</h4>

                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nama Produk</span>
                    <a href="{{ route('products.show', $review->product->slug) }}" target="_blank" class="font-bold text-blue-600 hover:underline block">
                        {{ $review->product->name }}
                    </a>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Kategori</span>
                    <p class="font-bold text-slate-800">{{ $review->product->category->name ?? '-' }}</p>
                </div>
            </div>

            <!-- BUYER INFO -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3 text-xs">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400">Data Pembeli</h4>

                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nama</span>
                    <p class="font-bold text-slate-900">{{ $review->user->name ?? '-' }}</p>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Email</span>
                    <p class="text-slate-700">{{ $review->user->email ?? '-' }}</p>
                </div>

                @if($review->order)
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nomor Pesanan</span>
                        <a href="{{ route('admin.pesanan.show', $review->order->id) }}" class="font-mono font-bold text-blue-600 hover:underline">
                            #{{ $review->order->order_number }}
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
