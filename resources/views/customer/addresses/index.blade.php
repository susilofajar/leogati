@extends('layouts.app')

@section('title', 'Buku Alamat Pengiriman - LEOGATISTORE')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ addModalOpen: false, editModalOpen: false, currentAddress: {} }">

    {{-- BREADCRUMBS --}}
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-[#0B5CFF]">Akun Saya</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Buku Alamat</span>
    </nav>

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#0B5CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Buku Alamat Pengiriman
            </h1>
            <p class="text-xs text-slate-500 mt-1">Kelola alamat tujuan pengiriman untuk mempermudah proses checkout pesanan Anda.</p>
        </div>
        <button @click="addModalOpen = true" 
            class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Alamat Baru
        </button>
    </div>

    {{-- ADDRESSES LIST --}}
    @if($addresses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($addresses as $addr)
                <div class="bg-white p-5 rounded-2xl border {{ $addr->is_primary ? 'border-[#0B5CFF] ring-1 ring-[#0B5CFF]/30' : 'border-slate-200' }} shadow-2xs flex flex-col justify-between space-y-4 relative">
                    
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">{{ $addr->recipient_name }}</h3>
                                @if($addr->is_primary)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-[#0B5CFF] border border-blue-200">
                                        Utama
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 font-medium">{{ $addr->phone_number }}</p>
                        <p class="text-xs text-slate-700 mt-2 leading-relaxed">{{ $addr->address_line }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}</p>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-3">
                            <button @click="currentAddress = {{ json_encode($addr) }}; editModalOpen = true" 
                                class="text-[#0B5CFF] hover:underline font-bold">
                                Ubah
                            </button>

                            @if(!$addr->is_primary)
                                <span class="text-slate-300">|</span>
                                <form action="{{ route('customer.addresses.destroy', $addr->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus alamat ini?')" class="text-rose-600 hover:underline font-bold">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if(!$addr->is_primary)
                            <form action="{{ route('customer.addresses.set_default', $addr->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold text-[11px] transition">
                                    Jadikan Utama
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-4 max-w-md mx-auto">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Belum Ada Alamat Tersimpan</h3>
                <p class="text-xs text-slate-500 mt-1">Tambahkan alamat pengiriman Anda sekarang untuk mempercepat checkout.</p>
            </div>
            <button @click="addModalOpen = true" class="px-5 py-2.5 bg-[#0B5CFF] text-white text-xs font-bold rounded-xl shadow-xs inline-block hover:bg-[#063B9E] transition">
                + Tambah Alamat Baru
            </button>
        </div>
    @endif

    {{-- MODAL TAMBAH ALAMAT --}}
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="addModalOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Tambah Alamat Pengiriman Baru</h3>
                <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('customer.addresses.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Penerima</label>
                        <input type="text" name="recipient_name" required placeholder="Budi Santoso"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone_number" required placeholder="081234567890"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                    <textarea name="address_line" rows="2" required placeholder="Jl. Jendral Sudirman No. 123, RT 01/RW 02"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden"></textarea>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                        <input type="text" name="city" required placeholder="Jakarta Pusat"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provinsi</label>
                        <input type="text" name="province" required placeholder="DKI Jakarta"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Pos</label>
                        <input type="text" name="postal_code" required placeholder="10110"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white rounded-xl font-bold transition shadow-xs">
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL UBAH ALAMAT --}}
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="editModalOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Ubah Alamat Pengiriman</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="'/akun/alamat/' + currentAddress.id" method="POST" class="space-y-3.5 text-xs">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Penerima</label>
                        <input type="text" name="recipient_name" x-model="currentAddress.recipient_name" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone_number" x-model="currentAddress.phone_number" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                    <textarea name="address_line" rows="2" x-model="currentAddress.address_line" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden"></textarea>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                        <input type="text" name="city" x-model="currentAddress.city" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provinsi</label>
                        <input type="text" name="province" x-model="currentAddress.province" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Pos</label>
                        <input type="text" name="postal_code" x-model="currentAddress.postal_code" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:border-[#0B5CFF] focus:bg-white focus:outline-hidden">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#0B5CFF] hover:bg-[#063B9E] text-white rounded-xl font-bold transition shadow-xs">
                        Perbarui Alamat
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
