@extends('layouts.admin')

@section('title', 'Detail Supplier — ' . $supplier->name)
@section('header_title', 'Detail Supplier')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.supplier.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200
                      bg-white text-slate-500 hover:text-[#0B5CFF] hover:border-blue-200
                      hover:bg-blue-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-xl font-extrabold text-slate-900">
                        {{ $supplier->name }}
                    </h1>

                    @if($supplier->is_active)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                     text-[10px] font-bold bg-emerald-100 text-emerald-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                     text-[10px] font-bold bg-rose-100 text-rose-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>

                <p class="text-xs text-slate-500 mt-1">
                    Supplier
                    <span class="font-mono font-bold text-[#0B5CFF]">
                        {{ $supplier->code }}
                    </span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.supplier.edit', $supplier) }}"
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                      border border-slate-200 bg-white text-slate-700 text-xs font-bold
                      hover:bg-slate-50 hover:border-slate-300 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                             M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Supplier
            </a>

            <a href="{{ route('admin.pembelian.create') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                      bg-[#0B5CFF] text-white text-xs font-bold shadow-sm
                      hover:bg-[#094dcc] transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Buat PO
            </a>
        </div>
    </div>


    {{-- SUPPLIER SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Status --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    Status Supplier
                </p>

                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600
                            flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016
                                 A11.955 11.955 0 0112 2.944
                                 a11.955 11.955 0 01-8.618 3.04
                                 A12.02 12.02 0 003 9c0 5.591
                                 3.824 10.29 9 11.622
                                 5.176-1.332 9-6.03 9-11.622
                                 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>

            <p class="text-2xl font-black text-slate-900 mt-2">
                {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
            </p>

            <p class="text-[11px] text-slate-500 mt-1">
                {{ $supplier->is_active ? 'Dapat digunakan untuk Purchase Order' : 'Tidak dapat digunakan untuk PO baru' }}
            </p>
        </div>


        {{-- PIC --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    Contact Person
                </p>

                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0B5CFF]
                            flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                                 M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>

            <p class="text-lg font-black text-slate-900 mt-2 truncate">
                {{ $supplier->pic_name ?? '-' }}
            </p>

            <p class="text-[11px] text-slate-500 mt-1">
                PIC / Penanggung Jawab Supplier
            </p>
        </div>


        {{-- Payment Terms --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    Syarat Pembayaran
                </p>

                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600
                            flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                                 3 .895 3 2-1.343 2-3 2m0-8c1.11 0
                                 2.08.402 2.599 1M12 8V7m0 1v8m0
                                 0v1m0-1c-1.11 0-2.08-.402-2.599-1
                                 M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <p class="text-2xl font-black text-slate-900 mt-2">
                {{ $supplier->payment_terms ?? 'NET30' }}
            </p>

            <p class="text-[11px] text-slate-500 mt-1">
                Ketentuan pembayaran supplier
            </p>
        </div>


        {{-- Kode Supplier --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-5">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    Kode Supplier
                </p>

                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600
                            flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M7 7h.01M7 3h5l9 9-5 5-9-9V3z"/>
                    </svg>
                </div>
            </div>

            <p class="text-xl font-black font-mono text-[#0B5CFF] mt-2">
                {{ $supplier->code }}
            </p>

            <p class="text-[11px] text-slate-500 mt-1">
                Identitas resmi supplier
            </p>
        </div>

    </div>


    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- INFORMASI PERUSAHAAN --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200
                    shadow-2xs overflow-hidden">

            <div class="p-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#0B5CFF]
                                flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2
                                     0 00-2 2v16m14 0h2m-2 0h-5m-4
                                     0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1
                                     4h1m-5 8v-4h4v4"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-slate-900">
                            Informasi Perusahaan
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            Detail kontak dan identitas supplier
                        </p>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-slate-100">

                <div class="px-5 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Nama Supplier
                    </p>
                    <p class="text-sm font-semibold text-slate-900 mt-1">
                        {{ $supplier->name }}
                    </p>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Contact Person
                    </p>
                    <p class="text-sm font-semibold text-slate-900 mt-1">
                        {{ $supplier->pic_name ?? '-' }}
                    </p>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Nomor Telepon
                    </p>
                    <p class="text-sm text-slate-700 mt-1">
                        {{ $supplier->phone ?? '-' }}
                    </p>
                </div>

                <div class="px-5 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Email
                    </p>

                    @if($supplier->email)
                        <a href="mailto:{{ $supplier->email }}"
                           class="text-sm text-[#0B5CFF] hover:underline mt-1 inline-block">
                            {{ $supplier->email }}
                        </a>
                    @else
                        <p class="text-sm text-slate-700 mt-1">-</p>
                    @endif
                </div>

                <div class="px-5 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        NPWP
                    </p>
                    <p class="text-sm font-mono text-slate-700 mt-1">
                        {{ $supplier->npwp ?? '-' }}
                    </p>
                </div>

                <div class="px-5 py-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Alamat Lengkap
                    </p>

                    <p class="text-sm leading-relaxed text-slate-700 mt-1">
                        {{ $supplier->address ?? '-' }}

                        @if($supplier->city || $supplier->province)
                            <br>
                            {{ $supplier->city ?? '' }}
                            @if($supplier->province)
                                , {{ $supplier->province }}
                            @endif

                            @if($supplier->postal_code)
                                — {{ $supplier->postal_code }}
                            @endif
                        @endif
                    </p>
                </div>

            </div>
        </div>


        {{-- RIWAYAT PO --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200
                    shadow-2xs overflow-hidden">

            <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">
                        Riwayat Purchase Order
                    </h3>

                    <p class="text-xs text-slate-500 mt-0.5">
                        Daftar transaksi PO dengan supplier ini
                    </p>
                </div>

                <a href="{{ route('admin.pembelian.create') }}"
                   class="inline-flex items-center px-3 py-2 rounded-xl
                          bg-[#0B5CFF] text-white text-[11px] font-bold
                          hover:bg-[#094dcc] transition whitespace-nowrap">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat PO
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-[10px] font-bold
                                       uppercase tracking-wider text-slate-500">
                                Nomor PO
                            </th>

                            <th class="px-4 py-3 text-left text-[10px] font-bold
                                       uppercase tracking-wider text-slate-500">
                                Gudang
                            </th>

                            <th class="px-4 py-3 text-center text-[10px] font-bold
                                       uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-4 py-3 text-right text-[10px] font-bold
                                       uppercase tracking-wider text-slate-500">
                                Total
                            </th>

                            <th class="px-5 py-3 text-right"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($supplier->purchaseOrders as $po)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.pembelian.show', $po->id) }}"
                                       class="text-xs font-bold font-mono text-[#0B5CFF]
                                              hover:underline">
                                        #{{ $po->po_number }}
                                    </a>

                                    <p class="text-[10px] text-slate-400 mt-1">
                                        {{ tgl_indo($po->created_at) }}
                                    </p>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="text-xs font-semibold text-slate-800">
                                        {{ $po->warehouse->name ?? '-' }}
                                    </p>

                                    @if($po->warehouse?->code)
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">
                                            {{ $po->warehouse->code }}
                                        </p>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1
                                                 rounded-full text-[10px] font-bold
                                                 bg-slate-100 text-slate-700">
                                        {{ $po->status_label }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-right">
                                    <p class="text-xs font-black text-slate-900">
                                        {{ rupiah($po->total_amount) }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.pembelian.show', $po->id) }}"
                                       class="inline-flex items-center px-2.5 py-1.5
                                              rounded-lg border border-slate-200
                                              text-[10px] font-bold text-slate-600
                                              hover:bg-blue-50 hover:text-[#0B5CFF]
                                              hover:border-blue-200 transition">
                                        Detail
                                        <svg class="w-3.5 h-3.5 ml-1" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">

                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-50
                                                text-slate-400 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 14l6-6m4 9a2 2 0
                                                     01-2 2H7a2 2 0 01-2-2V7
                                                     a2 2 0 012-2h10a2 2 0
                                                     012 2v8z"/>
                                        </svg>
                                    </div>

                                    <p class="text-xs font-semibold text-slate-600">
                                        Belum ada Purchase Order
                                    </p>

                                    <p class="text-[11px] text-slate-400 mt-1">
                                        Supplier ini belum memiliki riwayat PO.
                                    </p>

                                    <a href="{{ route('admin.pembelian.create') }}"
                                       class="inline-flex items-center mt-4 px-3 py-2
                                              rounded-xl bg-[#0B5CFF] text-white
                                              text-[11px] font-bold hover:bg-[#094dcc]
                                              transition">
                                        Buat Purchase Order
                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection