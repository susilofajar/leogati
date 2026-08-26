@extends('layouts.admin')

@section('header_title', 'Manajemen Supplier')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Supplier / Pemasok</h2>
            <p class="text-xs text-slate-500 mt-1">
                Daftar vendor dan distributor resmi mitra LEOGATISTORE.
            </p>
        </div>

        <a href="{{ route('admin.supplier.create') }}"
            class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Supplier
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs font-semibold">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- FILTER --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">
        <form method="GET"
            action="{{ route('admin.supplier.index') }}"
            class="flex flex-col sm:flex-row gap-3">

            {{-- SEARCH --}}
            <div class="flex-1 relative">
                <input
                    type="text"
                    name="cari"
                    value="{{ request('cari') }}"
                    placeholder="Cari nama supplier, kode, email..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs
                           focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">

                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <button type="submit"
                class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl
                       hover:bg-slate-800 transition flex items-center justify-center gap-1.5">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>

                Cari
            </button>

            @if(request()->has('cari') && request('cari') !== '')
                <a href="{{ route('admin.supplier.index') }}"
                    class="px-3 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl
                           hover:bg-slate-200 transition flex items-center justify-center">
                    Reset
                </a>
            @endif

        </form>
    </div>

    {{-- SUPPLIER TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-5 py-3.5">Supplier</th>
                        <th class="px-5 py-3.5">Kode</th>
                        <th class="px-5 py-3.5">PIC / Kontak</th>
                        <th class="px-5 py-3.5">Kota</th>
                        <th class="px-5 py-3.5 text-center">Pembayaran</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($suppliers as $sup)

                        <tr class="hover:bg-slate-50/80 transition">

                            {{-- SUPPLIER --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">

                                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-[#0B5CFF]
                                                font-black text-xs flex items-center justify-center
                                                border border-blue-100 uppercase">
                                        {{ strtoupper(substr($sup->name, 0, 2)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 truncate max-w-[220px]">
                                            {{ $sup->name }}
                                        </div>

                                        @if($sup->email)
                                            <div class="text-[11px] text-slate-500 truncate max-w-[220px]">
                                                {{ $sup->email }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            {{-- CODE --}}
                            <td class="px-5 py-4">
                                <span class="font-mono font-semibold text-slate-600">
                                    {{ $sup->code }}
                                </span>
                            </td>

                            {{-- PIC --}}
                            <td class="px-5 py-4">

                                <div class="font-semibold text-slate-700">
                                    {{ $sup->pic_name ?? '-' }}
                                </div>

                                @if($sup->phone)
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $sup->phone }}
                                    </div>
                                @endif

                            </td>

                            {{-- CITY --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $sup->city ?? '-' }}
                            </td>

                            {{-- PAYMENT TERMS --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                             text-[10px] font-bold bg-slate-100
                                             text-slate-700 border border-slate-200">
                                    {{ $sup->payment_terms ?? 'NET30' }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">

                                @if($sup->is_active)

                                    <span class="inline-flex items-center gap-1 px-2.5 py-1
                                                 rounded-full text-[10px] font-bold
                                                 bg-emerald-50 text-emerald-700
                                                 border border-emerald-200">

                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1 px-2.5 py-1
                                                 rounded-full text-[10px] font-bold
                                                 bg-slate-100 text-slate-500
                                                 border border-slate-200">

                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>

                                @endif

                            </td>

                            {{-- ACTION --}}
                            <td class="px-5 py-4 text-right">

                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('admin.supplier.show', $sup) }}"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-900
                                               text-slate-700 hover:text-white font-bold
                                               rounded-lg transition">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.supplier.edit', $sup) }}"
                                        class="px-3 py-1.5 bg-blue-50 hover:bg-[#0B5CFF]
                                               text-[#0B5CFF] hover:text-white font-bold
                                               rounded-lg transition">
                                        Edit
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="w-12 h-12 rounded-2xl bg-slate-100
                                                text-slate-400 flex items-center justify-center mb-3">

                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2
                                                   M12 11a4 4 0 100-8 4 4 0 000 8z
                                                   M21 21v-2a4 4 0 00-3-3.87
                                                   M16 3.13a4 4 0 010 7.75"/>
                                        </svg>

                                    </div>

                                    <div class="font-bold text-slate-700 text-sm">
                                        Belum ada supplier
                                    </div>

                                    <div class="text-xs text-slate-400 mt-1">
                                        Belum ada supplier yang terdaftar pada sistem.
                                    </div>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if($suppliers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $suppliers->links() }}
            </div>
        @endif

    </div>

</div>
@endsection