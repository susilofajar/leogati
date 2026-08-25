@extends('layouts.admin')

@section('header_title', 'Manajemen Klaim Garansi & Layanan Purna Jual')

@section('content')
<div class="space-y-6">

    <!-- HEADER & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tiket Klaim Garansi Masuk</h2>
            <p class="text-xs text-slate-500">Kelola proses klaim garansi, diagnosis teknis, dan resolusi perbaikan produk pelanggan.</p>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.garansi.index') }}" 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ !request('status') ? 'bg-[#0B5CFF] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Klaim
            </a>
            @foreach($statuses as $key => $label)
                <a href="{{ route('admin.garansi.index', ['status' => $key, 'q' => request('q')]) }}" 
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === $key ? 'bg-[#0B5CFF] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.garansi.index') }}" method="GET" class="flex items-center space-x-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari No. Klaim, SN, Nama..." 
                class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF] w-60">
            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                Cari
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($claims->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-4">No. Tiket Klaim</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Produk & No. Seri</th>
                            <th class="py-3 px-4">Kategori Masalah</th>
                            <th class="py-3 px-4">Tanggal Masuk</th>
                            <th class="py-3 px-4">Status Klaim</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($claims as $claim)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('admin.garansi.show', $claim->id) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                        {{ $claim->claim_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-800">{{ $claim->customer->name ?? '-' }}</p>
                                    <span class="text-[11px] text-slate-400">{{ $claim->customer->email ?? '' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-800 line-clamp-1">
                                        {{ $claim->serialNumber->productVariant->product->name ?? 'Produk' }}
                                    </p>
                                    <span class="text-[11px] text-slate-500 font-mono">S/N: {{ $claim->serialNumber->serial_number ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        {{ $claim->issue_category_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">
                                    {{ tgl_indo($claim->submitted_at) }}
                                </td>
                                <td class="py-3.5 px-4">
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
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $badgeClasses[$claim->status] ?? 'bg-slate-50 text-slate-700' }}">
                                        {{ $claim->status_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('admin.garansi.show', $claim->id) }}" 
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-[#0B5CFF] hover:text-white text-slate-700 font-bold rounded-lg transition inline-block">
                                        Proses
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
            <div class="p-8 text-center text-slate-500">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <p class="font-bold text-sm text-slate-700">Tidak ada data klaim garansi yang cocok</p>
                <p class="text-xs text-slate-400 mt-1">Belum ada pengajuan klaim garansi dengan kriteria filter saat ini.</p>
            </div>
        @endif
    </div>

</div>
@endsection
