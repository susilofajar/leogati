@extends('layouts.app')

@section('title', 'Riwayat Klaim Garansi Saya')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

    <!-- BREADCRUMB & HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex text-xs text-slate-500 space-x-2 mb-1">
                <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-600">Akun Saya</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Klaim Garansi</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-slate-900">
                Riwayat & Status Klaim Garansi
            </h1>
            <p class="text-xs text-slate-500">
                Pantau progres peninjauan, diagnosis, dan proses perbaikan unit perangkat Anda.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('warranty.check') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Cek Nomor Seri
            </a>
            <a href="{{ route('warranty.claim_form') }}" class="px-4 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Ajukan Klaim Baru
            </a>
        </div>
    </div>

    <!-- CLAIMS LIST TABLE / CARDS -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        @if($claims->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-6">Nomor Tiket Klaim</th>
                            <th class="py-3.5 px-6">Produk & Nomor Seri</th>
                            <th class="py-3.5 px-6">Kategori Kendala</th>
                            <th class="py-3.5 px-6">Tanggal Diajukan</th>
                            <th class="py-3.5 px-6">Status Progres</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($claims as $claim)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-4 px-6">
                                    <span class="font-mono font-bold text-blue-600 block">{{ $claim->claim_number }}</span>
                                    @if($claim->order)
                                        <span class="text-[10px] text-slate-400">Order #{{ $claim->order->order_number }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-slate-800 line-clamp-1">
                                        {{ $claim->serialNumber->productVariant->product->name ?? 'Produk' }}
                                    </p>
                                    <span class="text-[11px] text-slate-500 font-mono">S/N: {{ $claim->serialNumber->serial_number ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        {{ $claim->issue_category_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-slate-500">
                                    {{ tgl_indo($claim->submitted_at) }}
                                </td>
                                <td class="py-4 px-6">
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
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badgeClasses[$claim->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                        {{ $claim->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('customer.warranty.show', $claim->claim_number) }}" 
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-[#0B5CFF] hover:text-white text-slate-700 font-bold rounded-lg transition inline-flex items-center">
                                        Detail Tiket
                                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $claims->links() }}
            </div>
        @else
            <div class="p-12 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Pengajuan Klaim Garansi</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                    Semua perangkat Anda dalam kondisi baik. Jika mengalami kendala teknis pada unit yang Anda beli, Anda dapat mengajukan klaim garansi di sini.
                </p>
                <div class="pt-2">
                    <a href="{{ route('warranty.claim_form') }}" class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-sm transition inline-flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajukan Klaim Garansi
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
