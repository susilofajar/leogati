@extends('layouts.admin')

@section('header_title', 'Proses Klaim Garansi #' . $claim->claim_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- TOP NAV -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.garansi.index') }}" class="text-xs text-slate-500 hover:text-blue-600 font-bold flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Klaim Garansi
        </a>

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
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClasses[$claim->status] ?? 'bg-slate-50 text-slate-700' }}">
            Status: {{ $claim->status_label }}
        </span>
    </div>

    <!-- MAIN GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- LEFT COLUMN: CLAIM DETAILS & STATUS UPDATE (2 COLS) -->
        <div class="md:col-span-2 space-y-6">

            <!-- CLAIM INFO CARD -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-sm text-slate-800">Rincian Pengajuan Tiket</h3>
                    <span class="text-xs font-mono font-bold text-blue-600">{{ $claim->claim_number }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold text-[10px] uppercase">Kategori Kendala</span>
                        <p class="font-bold text-slate-800">{{ $claim->issue_category_label }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold text-[10px] uppercase">Waktu Masuk</span>
                        <p class="font-bold text-slate-800">{{ tgl_indo($claim->submitted_at) }}</p>
                    </div>
                </div>

                <div>
                    <span class="text-slate-400 block font-semibold text-[10px] uppercase mb-1">Deskripsi Kerusakan dari Pelanggan</span>
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                        {{ $claim->issue_description }}
                    </div>
                </div>
            </div>

            <!-- STATUS UPDATE FORM CARD -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <h3 class="font-bold text-sm text-slate-800 border-b border-slate-100 pb-3">
                    Perbarui Status & Diagnosis Teknis
                </h3>

                @if($claim->isTerminal())
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                        <p class="font-bold text-slate-800">Tiket Klaim Selesai / Ditutup</p>
                        <p class="text-[11px] leading-relaxed">
                            Klaim ini telah mencapai status terminal (<strong>{{ $claim->status_label }}</strong>).
                        </p>
                        @if($claim->resolution)
                            <div class="mt-2 p-3 bg-white border border-slate-200 rounded-lg">
                                <span class="font-bold text-[10px] uppercase text-slate-400 block mb-1">Resolusi Akhir:</span>
                                <p class="text-xs text-slate-800">{{ $claim->resolution }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <form action="{{ route('admin.garansi.update_status', $claim->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status" class="block text-xs font-bold text-slate-700 mb-1">
                                Ubah Status Klaim <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:outline-hidden focus:border-[#0B5CFF]">
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $claim->status) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admin_notes" class="block text-xs font-bold text-slate-700 mb-1">
                                Catatan Internal Staf & Hasil Pengecekan
                            </label>
                            <textarea name="admin_notes" id="admin_notes" rows="3"
                                placeholder="Catatan internal tim teknisi (mis. hasil pengujian PSU, voltase, kondisi fisik internal)..."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">{{ old('admin_notes', $claim->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="resolution" class="block text-xs font-bold text-slate-700 mb-1">
                                Solusi / Resolusi untuk Pelanggan (Wajib jika status selesai/ditolak)
                            </label>
                            <textarea name="resolution" id="resolution" rows="3"
                                placeholder="Penjelasan solusi untuk pelanggan (mis. motherboard berhasil diganti baru, thermal paste dibersihkan, unit sudah dites 24 jam)..."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#0B5CFF]">{{ old('resolution', $claim->resolution) }}</textarea>
                            @error('resolution')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-lg transition shadow-xs">
                                Simpan Perubahan Status
                            </button>
                        </div>
                    </form>
                @endif
            </div>

        </div>

        <!-- RIGHT COLUMN: PRODUCT & CUSTOMER INFO (1 COL) -->
        <div class="space-y-6">

            <!-- PRODUCT & SN INFO -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3 text-xs">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400">Unit Produk</h4>

                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Produk</span>
                    <p class="font-bold text-slate-900">{{ $claim->serialNumber->productVariant->product->name ?? 'Produk' }}</p>
                    <p class="text-slate-500">{{ $claim->serialNumber->productVariant->name ?? '' }}</p>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nomor Seri (S/N)</span>
                    <a href="{{ route('admin.nomor_seri.show', $claim->serial_number_id) }}" class="font-mono font-bold text-blue-600 hover:underline">
                        {{ $claim->serialNumber->serial_number ?? '-' }}
                    </a>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Masa Garansi</span>
                    <p class="font-bold {{ $claim->serialNumber->warranty_expires_at && $claim->serialNumber->warranty_expires_at->isPast() ? 'text-rose-600' : 'text-emerald-700' }}">
                        {{ $claim->serialNumber->warranty_expires_at ? tgl_indo($claim->serialNumber->warranty_expires_at) : 'Seumur Hidup' }}
                    </p>
                </div>
            </div>

            <!-- CUSTOMER INFO -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3 text-xs">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400">Data Pelanggan</h4>

                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nama Pemilik</span>
                    <p class="font-bold text-slate-900">{{ $claim->customer->name ?? '-' }}</p>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Email</span>
                    <p class="text-slate-700">{{ $claim->customer->email ?? '-' }}</p>
                </div>

                @if($claim->order)
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Faktur Pesanan Asal</span>
                        <a href="{{ route('admin.pesanan.show', $claim->order->id) }}" class="font-mono font-bold text-blue-600 hover:underline">
                            #{{ $claim->order->order_number }}
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
