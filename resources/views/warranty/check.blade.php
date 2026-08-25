@extends('layouts.app')

@section('title', 'Pengecekan Status Garansi Resmi Produk')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <!-- TITLE & HEADER -->
    <div class="text-center space-y-3">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 text-[#0B5CFF] text-xs font-bold border border-blue-100">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            Layanan Purna Jual Resmi
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
            Pengecekan Status & Masa Berlaku Garansi
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto leading-relaxed">
            Periksa keaslian produk, tanggal pembelian, dan masa aktif garansi resmi perangkat teknologi Anda yang dibeli di LEOGATISTORE.
        </p>
    </div>

    <!-- SEARCH BOX -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md">
        <form action="{{ route('warranty.check') }}" method="GET" class="space-y-4">
            <div>
                <label for="sn" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nomor Seri Produk (Serial Number / S/N) <span class="text-rose-500">*</span>
                </label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="sn" id="sn" value="{{ $serialNumber ?? '' }}" required
                        placeholder="Contoh: SN-ROG-2026-98124 atau ASUS123456789"
                        class="flex-1 px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono uppercase focus:outline-hidden focus:border-[#0B5CFF] focus:ring-3 focus:ring-blue-100 transition">
                    <button type="submit"
                        class="px-6 py-3 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Periksa Garansi
                    </button>
                </div>
                <p class="mt-2 text-[11px] text-slate-400">
                    * Nomor seri biasanya tertera pada stiker barcode di bagian bawah bodi laptop, kotak kemasan asli, atau nota faktur pembelian Anda.
                </p>
            </div>
        </form>

        <!-- RESULT SECTION -->
        @if(isset($result) && $result['searched'])
            <div class="mt-8 pt-8 border-t border-slate-100">
                @if($result['found'])
                    <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-3">
                        <div class="flex items-center text-emerald-800 font-bold text-sm">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Garansi Resmi Terdaftar & Aktif
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs pt-2">
                            <div>
                                <span class="text-slate-500 text-[10px] uppercase font-bold">Nomor Seri</span>
                                <p class="font-mono font-bold text-slate-800">{{ $result['serial_number'] }}</p>
                            </div>
                            <div>
                                <span class="text-slate-500 text-[10px] uppercase font-bold">Produk</span>
                                <p class="font-bold text-slate-800">{{ $result['product_name'] ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-slate-500 text-[10px] uppercase font-bold">Tanggal Pembelian</span>
                                <p class="font-bold text-slate-800">{{ $result['purchase_date'] ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-slate-500 text-[10px] uppercase font-bold">Berlaku Hingga</span>
                                <p class="font-bold text-emerald-700">{{ $result['warranty_end'] ?? '-' }}</p>
                            </div>
                        </div>

                        @if(!empty($result['can_claim']))
                            <div class="pt-3 border-t border-emerald-200/60 flex items-center justify-between">
                                <span class="text-[11px] text-emerald-800">
                                    Perangkat mengalami masalah teknis? Anda dapat mengajukan klaim perbaikan resmi secara online.
                                </span>
                                <a href="{{ route('warranty.claim_form', ['sn' => $result['serial_number']]) }}" 
                                    class="px-4 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shrink-0 shadow-xs inline-flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Ajukan Klaim Garansi
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start text-slate-700 font-bold text-sm">
                            <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Nomor Seri Tidak Ditemukan: <code class="font-mono ml-1 text-slate-900">{{ $result['serial_number'] }}</code>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            {{ $result['message'] }}
                        </p>
                        <p class="text-xs text-slate-500">
                            Butuh bantuan klaim garansi? Hubungi Customer Care kami melalui WhatsApp di <strong>0812-3456-7890</strong>.
                        </p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- GUIDE HOW TO FIND SERIAL NUMBER -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-2">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0B5CFF] flex items-center justify-center font-bold text-xs">
                1
            </div>
            <h4 class="text-xs font-bold text-slate-900">Bodi Bawah Perangkat</h4>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                Periksa stiker barcode di bagian bawah casing laptop atau bodi belakang monitor bertuliskan S/N atau Serial No.
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-2">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0B5CFF] flex items-center justify-center font-bold text-xs">
                2
            </div>
            <h4 class="text-xs font-bold text-slate-900">Kemasan / Box Asli</h4>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                Nomor seri unik juga tercetak pada label pabrik di sisi luar dus kemasan produk resmi.
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-2">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0B5CFF] flex items-center justify-center font-bold text-xs">
                3
            </div>
            <h4 class="text-xs font-bold text-slate-900">Faktur & Nota Pembelian</h4>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                Lihat rincian invoice digital di akun LEOGATISTORE Anda atau salinan struk fisik yang kami sertakan saat pengiriman.
            </p>
        </div>

    </div>

</div>
@endsection
