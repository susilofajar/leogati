@extends('layouts.app')

@section('title', 'Detail Tiket Klaim Garansi #' . $claim->claim_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

    <!-- BREADCRUMB & HEADER -->
    <div class="space-y-2">
        <nav class="flex text-xs text-slate-500 space-x-2">
            <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-600">Akun Saya</a>
            <span>/</span>
            <a href="{{ route('customer.warranty.index') }}" class="hover:text-blue-600">Klaim Garansi</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold font-mono">{{ $claim->claim_number }}</span>
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">
                    Tiket Klaim: <span class="font-mono text-[#0B5CFF]">#{{ $claim->claim_number }}</span>
                </h1>
                <p class="text-xs text-slate-500">
                    Diajukan pada {{ tgl_indo($claim->submitted_at) }} &bull; S/N: <span class="font-mono font-bold text-slate-700">{{ $claim->serialNumber->serial_number ?? '-' }}</span>
                </p>
            </div>

            @php
                $badgeClasses = [
                    'submitted'  => 'bg-amber-50 text-amber-700 border-amber-200',
                    'reviewing'  => 'bg-blue-50 text-blue-700 border-blue-200',
                    'approved'   => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'in_repair'  => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                    'repaired'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'replaced'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'rejected'   => 'bg-rose-50 text-rose-700 border-rose-200',
                    'closed'     => 'bg-slate-100 text-slate-700 border-slate-200',
                ];
            @endphp
            <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold border {{ $badgeClasses[$claim->status] ?? 'bg-slate-50 text-slate-700' }}">
                <span class="w-2 h-2 rounded-full mr-2 bg-current animate-pulse"></span>
                Status: {{ $claim->status_label }}
            </span>
        </div>
    </div>

    <!-- RESOLUTION ALERT IF RESOLVED/REJECTED -->
    @if($claim->resolution)
        <div class="p-5 rounded-2xl {{ in_array($claim->status, ['repaired', 'replaced']) ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900' }} border space-y-2">
            <h4 class="font-bold text-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Hasil & Resolusi Klaim Garansi Tim Teknisi
            </h4>
            <p class="text-xs leading-relaxed whitespace-pre-line">{{ $claim->resolution }}</p>
            @if($claim->resolved_at)
                <p class="text-[11px] opacity-75">Diselesaikan pada: {{ tgl_indo($claim->resolved_at) }}</p>
            @endif
        </div>
    @endif

    <!-- 2 COLUMN DETAILS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- MAIN CLAIM DETAILS (2 COLS) -->
        <div class="md:col-span-2 space-y-6">

            <!-- ISSUE DETAIL CARD -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Rincian Laporan Kerusakan</h3>
                
                <div>
                    <span class="text-[11px] text-slate-400 uppercase font-semibold">Kategori Kendala</span>
                    <p class="text-sm font-bold text-slate-800">{{ $claim->issue_category_label }}</p>
                </div>

                <div>
                    <span class="text-[11px] text-slate-400 uppercase font-semibold">Deskripsi Kerusakan dari Anda</span>
                    <div class="mt-1 p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $claim->issue_description }}
                    </div>
                </div>

                @if($claim->admin_notes && auth()->user()->isAdmin())
                    <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-xl text-xs text-amber-900">
                        <span class="font-bold block mb-1">Catatan Internal Staf:</span>
                        {{ $claim->admin_notes }}
                    </div>
                @endif
            </div>

            <!-- TIMELINE PROGRESS -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Tahapan Proses Klaim</h3>

                <div class="space-y-4 text-xs">
                    <!-- STEP 1: SUBMITTED -->
                    <div class="flex items-start space-x-3">
                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-[11px] shrink-0">
                            ✓
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">Tiket Klaim Diajukan</p>
                            <p class="text-[11px] text-slate-500">{{ tgl_indo($claim->submitted_at) }}</p>
                        </div>
                    </div>

                    <!-- STEP 2: REVIEWING -->
                    <div class="flex items-start space-x-3">
                        <div class="w-7 h-7 rounded-full {{ $claim->reviewed_at || in_array($claim->status, ['reviewing', 'approved', 'in_repair', 'repaired', 'replaced', 'closed']) ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center font-bold text-[11px] shrink-0">
                            {{ $claim->reviewed_at || in_array($claim->status, ['reviewing', 'approved', 'in_repair', 'repaired', 'replaced', 'closed']) ? '✓' : '2' }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">Peninjauan & Verifikasi Dokumen</p>
                            <p class="text-[11px] text-slate-500">{{ $claim->reviewed_at ? tgl_indo($claim->reviewed_at) : 'Tim teknis memeriksa keaslian dan masa garansi' }}</p>
                        </div>
                    </div>

                    <!-- STEP 3: REPAIR OR RESOLUTION -->
                    <div class="flex items-start space-x-3">
                        <div class="w-7 h-7 rounded-full {{ in_array($claim->status, ['repaired', 'replaced', 'closed']) ? 'bg-emerald-500 text-white' : (in_array($claim->status, ['in_repair', 'approved']) ? 'bg-[#0B5CFF] text-white animate-pulse' : 'bg-slate-200 text-slate-400') }} flex items-center justify-center font-bold text-[11px] shrink-0">
                            {{ in_array($claim->status, ['repaired', 'replaced', 'closed']) ? '✓' : '3' }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">Perbaikan / Penggantian Unit</p>
                            <p class="text-[11px] text-slate-500">
                                @if(in_array($claim->status, ['repaired', 'replaced']))
                                    Selesai — Unit siap diambil atau dikirim kembali
                                @elseif($claim->status === 'in_repair')
                                    Sedang ditangani oleh teknisi resmi
                                @elseif($claim->status === 'rejected')
                                    Klaim ditolak (tidak memenuhi ketentuan garansi)
                                @else
                                    Menunggu penerimaan unit fisik di service center
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- SIDEBAR PRODUCT & HELP (1 COL) -->
        <div class="space-y-6">

            <!-- PRODUCT INFO -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Perangkat</h3>
                
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Nama Produk</span>
                    <p class="text-xs font-bold text-slate-900">
                        {{ $claim->serialNumber->productVariant->product->name ?? 'Produk' }}
                    </p>
                    <p class="text-xs text-slate-600">{{ $claim->serialNumber->productVariant->name ?? '' }}</p>
                </div>

                <div class="space-y-1 pt-2 border-t border-slate-100">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Nomor Seri (S/N)</span>
                    <p class="text-xs font-mono font-bold text-slate-900">{{ $claim->serialNumber->serial_number ?? '-' }}</p>
                </div>

                <div class="space-y-1 pt-2 border-t border-slate-100">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Masa Berlaku Garansi</span>
                    <p class="text-xs font-bold text-emerald-700">
                        {{ $claim->serialNumber->warranty_expires_at ? tgl_indo($claim->serialNumber->warranty_expires_at) : 'Seumur Hidup' }}
                    </p>
                </div>

                @if($claim->order)
                    <div class="space-y-1 pt-2 border-t border-slate-100">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold">Faktur Pembelian</span>
                        <a href="{{ route('customer.orders.show', $claim->order->order_number) }}" class="text-xs text-blue-600 font-bold hover:underline block font-mono">
                            #{{ $claim->order->order_number }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- NEED HELP CARD -->
            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 text-xs text-slate-600 space-y-3">
                <h4 class="font-bold text-slate-800">Butuh Bantuan Service?</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Jika Anda memiliki pertanyaan seputar pengiriman unit atau estimasi waktu perbaikan, hubungi WhatsApp Service Center kami:
                </p>
                <div class="pt-1">
                    <a href="https://wa.me/6281234567890" target="_blank" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs flex items-center justify-center transition shadow-xs">
                        Chat WhatsApp Service Center
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
