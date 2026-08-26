@extends('layouts.admin')

@section('header_title', 'Laporan Pelanggan')

@section('content')
<div class="space-y-6">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-4">

        <div>
            <div class="flex items-center gap-2 mb-2">

                <span class="inline-flex items-center justify-center
                             w-7 h-7 rounded-lg bg-blue-50 text-[#0B5CFF]">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                 M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                 M7 20H2v-2a3 3 0 015.356-1.857
                                 M7 20v-2c0-.656.126-1.283.356-1.857
                                 m0 0a5.002 5.002 0 019.288 0
                                 M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3
                                 a2 2 0 11-4 0 2 2 0 014 0zM7 10
                                 a2 2 0 11-4 0 2 2 0 014 0z"/>

                    </svg>

                </span>

                <span class="text-[10px] font-bold uppercase
                             tracking-[0.15em] text-slate-400">

                    Customer Analytics

                </span>

            </div>

            <h2 class="text-2xl font-black tracking-tight text-slate-900">
                Laporan Pelanggan
            </h2>

            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Analisis pertumbuhan pelanggan baru dan identifikasi
                pelanggan dengan nilai transaksi tertinggi.
            </p>
        </div>

        <div class="flex items-center gap-2">

            <span class="inline-flex items-center gap-2 px-3 py-2
                         rounded-xl bg-emerald-50 border border-emerald-100
                         text-[11px] font-bold text-emerald-700">

                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                Customer Data Aktif

            </span>

            <span class="px-3 py-2 rounded-xl bg-slate-100
                         text-[11px] font-bold text-slate-600">

                {{ now()->translatedFormat('d F Y') }}

            </span>

        </div>

    </div>


    {{-- =========================================================
         FILTER
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-xl bg-blue-50
                            text-[#0B5CFF] flex items-center
                            justify-center">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0
                                 011 1v2.586a1 1 0 01-.293.707l-6.414
                                 6.414A1 1 0 0014 14.414V19l-4 2v-6.586
                                 a1 1 0 00-.293-.707L3.293 7.293A1 1
                                 0 013 6.586V4z"/>

                    </svg>

                </div>

                <div>

                    <h3 class="text-sm font-black text-slate-900">
                        Filter Periode
                    </h3>

                    <p class="text-[11px] text-slate-500 mt-0.5">
                        Tentukan periode untuk melihat pertumbuhan pelanggan baru.
                    </p>

                </div>

            </div>

        </div>


        <form method="GET" class="p-5 bg-slate-50/70">

            <div class="grid grid-cols-1 sm:grid-cols-2
                        lg:grid-cols-4 gap-3 items-end">

                {{-- Dari --}}
                <div>

                    <label class="block text-[10px] font-black
                                  uppercase tracking-wider
                                  text-slate-500 mb-1.5">

                        Dari Tanggal

                    </label>

                    <input
                        type="date"
                        name="dari"
                        value="{{ $from->toDateString() }}"
                        class="w-full h-10 border border-slate-200
                               bg-white rounded-xl text-xs font-semibold
                               text-slate-700 px-3
                               focus:outline-none
                               focus:border-[#0B5CFF]
                               focus:ring-4 focus:ring-[#0B5CFF]/10
                               transition">

                </div>


                {{-- Sampai --}}
                <div>

                    <label class="block text-[10px] font-black
                                  uppercase tracking-wider
                                  text-slate-500 mb-1.5">

                        Sampai Tanggal

                    </label>

                    <input
                        type="date"
                        name="sampai"
                        value="{{ $to->toDateString() }}"
                        class="w-full h-10 border border-slate-200
                               bg-white rounded-xl text-xs font-semibold
                               text-slate-700 px-3
                               focus:outline-none
                               focus:border-[#0B5CFF]
                               focus:ring-4 focus:ring-[#0B5CFF]/10
                               transition">

                </div>


                {{-- Tampilkan --}}
                <button
                    type="submit"
                    class="h-10 px-5 inline-flex items-center
                           justify-center gap-2 bg-[#0B5CFF]
                           text-white rounded-xl text-xs font-black
                           hover:bg-[#063B9E] transition">

                    <svg class="w-3.5 h-3.5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-4.35-4.35m1.35-5.65
                                 a7 7 0 11-14 0 7 7 0 0114 0z"/>

                    </svg>

                    Tampilkan Data

                </button>


                {{-- Reset --}}
                <a
                    href="{{ route('admin.laporan.pelanggan') }}"
                    class="h-10 px-5 inline-flex items-center
                           justify-center gap-2 border border-slate-200
                           bg-white text-slate-600 rounded-xl
                           text-xs font-bold hover:bg-slate-50 transition">

                    <svg class="w-3.5 h-3.5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 4v5h5M20 20v-5h-5
                                 M5.07 9A7.5 7.5 0 0118.5 6.5
                                 M18.93 15A7.5 7.5 0 015.5 17.5"/>

                    </svg>

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- =========================================================
         SUMMARY KPI
    ========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- New Customers --}}
        <div class="relative overflow-hidden bg-white rounded-2xl
                    border border-slate-200 shadow-sm p-5">

            <div class="absolute -right-8 -top-8 w-28 h-28
                        rounded-full bg-blue-50"></div>

            <div class="relative">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-[10px] font-black uppercase
                                  tracking-[0.12em] text-slate-500">

                            Pelanggan Baru

                        </p>

                        <p class="text-3xl font-black tracking-tight
                                  text-[#0B5CFF] mt-2">

                            {{ number_format($newCustomers->count()) }}

                        </p>

                        <p class="text-[10px] text-slate-500 mt-1">

                            Registrasi pada periode terpilih

                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-blue-50
                                text-[#0B5CFF] flex items-center justify-center">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3
                                     m-2-5a4 4 0 11-8 0 4 4 0 018 0z
                                     M4 19a4 4 0 014-4h4a4 4 0
                                     014 4v1H4v-1z"/>

                        </svg>

                    </div>

                </div>

                <div class="mt-4">

                    <span class="inline-flex items-center gap-1.5
                                 px-2.5 py-1 rounded-lg bg-blue-50
                                 text-[9px] font-black text-[#0B5CFF]">

                        {{ tgl_indo($from) }} — {{ tgl_indo($to) }}

                    </span>

                </div>

            </div>

        </div>


        {{-- Total Orders --}}
        <div class="relative overflow-hidden bg-white rounded-2xl
                    border border-slate-200 shadow-sm p-5">

            <div class="absolute -right-8 -top-8 w-28 h-28
                        rounded-full bg-emerald-50"></div>

            <div class="relative">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-[10px] font-black uppercase
                                  tracking-[0.12em] text-slate-500">

                            Total Pesanan

                        </p>

                        <p class="text-3xl font-black tracking-tight
                                  text-slate-900 mt-2">

                            {{ number_format($newCustomers->sum('total_pesanan')) }}

                        </p>

                        <p class="text-[10px] text-slate-500 mt-1">

                            Pesanan dari pelanggan baru

                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-emerald-50
                                text-emerald-600 flex items-center justify-center">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2
                                     0 01-2-2V5a2 2 0 012-2h6l5
                                     5v11a2 2 0 01-2 2z"/>

                        </svg>

                    </div>

                </div>

                <div class="mt-4">

                    <span class="inline-flex items-center gap-1.5
                                 px-2.5 py-1 rounded-lg bg-emerald-50
                                 text-[9px] font-black text-emerald-600">

                        CUSTOMER CONVERSION

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TOP 10 CUSTOMERS
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">

            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3">

                <div class="flex items-start gap-3">

                    <div class="w-10 h-10 rounded-xl bg-amber-50
                                text-amber-600 flex items-center justify-center
                                shrink-0">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M16 11c1.657 0 3-1.343
                                     3-3s-1.343-3-3-3-3 1.343-3
                                     3 1.343 3 3 3z
                                     M8 11c1.657 0 3-1.343
                                     3-3S9.657 5 8 5 5 6.343
                                     5 8s1.343 3 3 3z
                                     M8 13c-2.21 0-4 1.79-4 4v1h8v-1
                                     c0-2.21-1.79-4-4-4zm8-2
                                     c-1.105 0-2.105.448-2.828 1.172
                                     A5.97 5.97 0 0116 16v1h6v-1
                                     c0-2.21-1.79-4-4-4z"/>

                        </svg>

                    </div>

                    <div>

                        <div class="flex items-center gap-2">

                            <h3 class="text-sm font-black text-slate-900">
                                Top 10 Pelanggan
                            </h3>

                            <span class="px-2 py-0.5 rounded-md
                                         bg-amber-50 text-amber-600
                                         text-[9px] font-black">

                                TOP VALUE

                            </span>

                        </div>

                        <p class="text-[11px] text-slate-500 mt-1">
                            Pelanggan dengan total nilai belanja kumulatif tertinggi.
                        </p>

                    </div>

                </div>

                <span class="px-3 py-1.5 rounded-lg bg-slate-100
                             text-[10px] font-black text-slate-500">

                    {{ $topCustomers->count() }} PELANGGAN

                </span>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 border-b border-slate-100">

                    <tr class="text-[9px] uppercase tracking-wider
                               font-black text-slate-400">

                        <th class="py-3.5 px-5 text-left">
                            Ranking
                        </th>

                        <th class="py-3.5 px-4 text-left">
                            Pelanggan
                        </th>

                        <th class="py-3.5 px-4 text-left">
                            Email
                        </th>

                        <th class="py-3.5 px-4 text-right">
                            Pesanan
                        </th>

                        <th class="py-3.5 px-5 text-right">
                            Total Belanja
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($topCustomers as $i => $customer)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- Ranking --}}
                            <td class="py-3.5 px-5">

                                @if($i < 3)

                                    <span @class([
                                        'inline-flex w-8 h-8 rounded-lg items-center justify-center font-black text-[10px]',
                                        'bg-amber-100 text-amber-700' => $i === 0,
                                        'bg-slate-200 text-slate-700' => $i === 1,
                                        'bg-orange-100 text-orange-700' => $i === 2,
                                    ])>

                                        {{ $i + 1 }}

                                    </span>

                                @else

                                    <span class="inline-flex w-8 h-8
                                                 rounded-lg bg-slate-50
                                                 items-center justify-center
                                                 font-black text-[10px]
                                                 text-slate-400">

                                        {{ $i + 1 }}

                                    </span>

                                @endif

                            </td>


                            {{-- Customer --}}
                            <td class="py-3.5 px-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-9 h-9 rounded-xl bg-blue-50
                                                text-[#0B5CFF]
                                                flex items-center justify-center
                                                text-[10px] font-black shrink-0">

                                        {{ strtoupper(substr($customer->name, 0, 2)) }}

                                    </div>

                                    <div class="min-w-0">

                                        <p class="font-bold text-slate-900
                                                  truncate max-w-[180px]">

                                            {{ $customer->name }}

                                        </p>

                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            Customer
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Email --}}
                            <td class="py-3.5 px-4">

                                <span class="text-slate-500">
                                    {{ $customer->email }}
                                </span>

                            </td>


                            {{-- Orders --}}
                            <td class="py-3.5 px-4 text-right">

                                <span class="inline-flex items-center
                                             px-2.5 py-1 rounded-lg
                                             bg-slate-100 text-slate-700
                                             font-black text-[10px]">

                                    {{ number_format($customer->total_pesanan) }}

                                </span>

                            </td>


                            {{-- Total --}}
                            <td class="py-3.5 px-5 text-right">

                                <span class="font-black text-[#0B5CFF]">

                                    {{ rupiah($customer->total_belanja ?? 0) }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="py-14 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="w-14 h-14 rounded-2xl bg-slate-100
                                                flex items-center justify-center mb-3">

                                        <svg class="w-7 h-7 text-slate-400"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M17 20h5v-2a3 3 0
                                                     00-5.356-1.857
                                                     M17 20H7m10 0v-2c0
                                                     -.656-.126-1.283-.356-1.857
                                                     M7 20H2v-2a3 3 0
                                                     015.356-1.857
                                                     M7 20v-2c0-.656.126-1.283
                                                     .356-1.857m0 0a5.002
                                                     5.002 0 019.288 0"/>

                                        </svg>

                                    </div>

                                    <p class="text-xs font-black text-slate-600">
                                        Belum ada data pelanggan
                                    </p>

                                    <p class="text-[10px] text-slate-400 mt-1">
                                        Belum ada pelanggan dengan riwayat pembelian.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         NEW CUSTOMERS
    ========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">

            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3">

                <div class="flex items-start gap-3">

                    <div class="w-10 h-10 rounded-xl bg-emerald-50
                                text-emerald-600 flex items-center justify-center
                                shrink-0">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3
                                     0h-3m-2-5a4 4 0 11-8 0 4 4
                                     0 018 0zM4 19a4 4 0 014-4h4a4
                                     4 0 014 4v1H4v-1z"/>

                        </svg>

                    </div>

                    <div>

                        <h3 class="text-sm font-black text-slate-900">
                            Pelanggan Baru
                        </h3>

                        <p class="text-[11px] text-slate-500 mt-1">

                            {{ $newCustomers->count() }}
                            pelanggan mendaftar antara
                            {{ tgl_indo($from) }} — {{ tgl_indo($to) }}

                        </p>

                    </div>

                </div>


                @if($newCustomers->count() > 0)

                    <span class="inline-flex items-center gap-1.5
                                 px-3 py-1.5 rounded-lg bg-emerald-50
                                 text-[10px] font-black text-emerald-600">

                        <span class="w-1.5 h-1.5 rounded-full
                                     bg-emerald-500"></span>

                        {{ $newCustomers->count() }} CUSTOMER BARU

                    </span>

                @endif

            </div>

        </div>


        @if($newCustomers->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full text-xs">

                    <thead class="bg-slate-50 border-b border-slate-100">

                        <tr class="text-[9px] uppercase tracking-wider
                                   font-black text-slate-400">

                            <th class="py-3.5 px-5 text-left">
                                Pelanggan
                            </th>

                            <th class="py-3.5 px-4 text-left">
                                Email
                            </th>

                            <th class="py-3.5 px-4 text-left">
                                Tanggal Daftar
                            </th>

                            <th class="py-3.5 px-5 text-right">
                                Jumlah Pesanan
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($newCustomers as $customer)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- Name --}}
                                <td class="py-3.5 px-5">

                                    <div class="flex items-center gap-3">

                                        <div class="w-9 h-9 rounded-xl bg-emerald-50
                                                    text-emerald-600
                                                    flex items-center justify-center
                                                    text-[10px] font-black">

                                            {{ strtoupper(substr($customer->name, 0, 2)) }}

                                        </div>

                                        <div>

                                            <p class="font-bold text-slate-900">
                                                {{ $customer->name }}
                                            </p>

                                            <p class="text-[10px] text-slate-400 mt-0.5">
                                                Customer baru
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Email --}}
                                <td class="py-3.5 px-4">

                                    <span class="text-slate-500">
                                        {{ $customer->email }}
                                    </span>

                                </td>


                                {{-- Register Date --}}
                                <td class="py-3.5 px-4">

                                    <div class="flex items-center gap-2">

                                        <div class="w-7 h-7 rounded-lg bg-slate-100
                                                    flex items-center justify-center">

                                            <svg class="w-3.5 h-3.5 text-slate-500"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M8 7V3m8 4V3m-9 8h10
                                                         M5 21h14a2 2 0 002-2V7
                                                         a2 2 0 00-2-2H5a2 2
                                                         0 00-2 2v12a2 2 0
                                                         002 2z"/>

                                            </svg>

                                        </div>

                                        <span class="text-[10px] font-semibold
                                                     text-slate-500">

                                            {{ tgl_indo($customer->created_at) }}

                                        </span>

                                    </div>

                                </td>


                                {{-- Orders --}}
                                <td class="py-3.5 px-5 text-right">

                                    @if($customer->total_pesanan > 0)

                                        <span class="inline-flex items-center gap-1.5
                                                     px-2.5 py-1.5 rounded-lg
                                                     bg-emerald-50 text-emerald-700
                                                     text-[10px] font-black">

                                            <span class="w-1.5 h-1.5 rounded-full
                                                         bg-emerald-500"></span>

                                            {{ $customer->total_pesanan }} pesanan

                                        </span>

                                    @else

                                        <span class="inline-flex items-center
                                                     px-2.5 py-1.5 rounded-lg
                                                     bg-slate-100 text-slate-400
                                                     text-[10px] font-bold">

                                            Belum ada pesanan

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="flex flex-col items-center py-14">

                <div class="w-14 h-14 rounded-2xl bg-slate-100
                            flex items-center justify-center mb-3">

                    <svg class="w-7 h-7 text-slate-400"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3
                                 0h-3m-2-5a4 4 0 11-8 0 4 4
                                 0 018 0zM4 19a4 4 0 014-4h4a4
                                 4 0 014 4v1H4v-1z"/>

                    </svg>

                </div>

                <p class="text-xs font-black text-slate-600">
                    Tidak ada pelanggan baru
                </p>

                <p class="text-[10px] text-slate-400 mt-1">
                    Tidak ada pelanggan yang mendaftar pada periode yang dipilih.
                </p>

            </div>

        @endif

    </div>

</div>
@endsection