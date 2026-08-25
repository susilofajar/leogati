@extends('layouts.app')

@section('title', 'Pembayaran & Checkout Pesanan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6"
    x-data="{
        courier: 'jne',
        weightGrams: {{ $cart->total_weight }},
        subtotal: {{ $cart->subtotal }},
        discount: {{ $discountAmount ?? 0 }},
        rates: {
            'jne': 15000,
            'sicepat': 14000,
            'jnt': 16000
        },
        get shippingCost() {
            let kg = Math.max(1, Math.ceil(this.weightGrams / 1000));
            return (this.rates[this.courier] || 15000) * kg;
        },
        get grandTotal() {
            return Math.max(0, this.subtotal + this.shippingCost - this.discount);
        },
        formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
    }">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs font-semibold text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a>
        <span class="mx-2 text-slate-400">/</span>
        <a href="{{ route('cart.index') }}" class="hover:text-[#0B5CFF]">Keranjang</a>
        <span class="mx-2 text-slate-400">/</span>
        <span class="text-slate-900 font-bold">Kasir & Pembayaran</span>
    </nav>

    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Penyelesaian Pesanan (Checkout)</h1>
        <p class="text-xs text-slate-500 mt-0.5">Lengkapi alamat pengiriman dan pilih metode pembayaran resmi</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf

        <!-- LEFT: FORM DATA -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- 1. ALAMAT PENGIRIMAN -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center space-x-3 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-full bg-[#0B5CFF] text-white flex items-center justify-center font-black text-xs">1</span>
                    <h2 class="text-sm font-extrabold text-slate-900">Alamat Pengiriman & Penerima</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="recipient_name" class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Lengkap Penerima <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="recipient_name" id="recipient_name" 
                            value="{{ old('recipient_name', $primaryAddress ? $primaryAddress->recipient_name : $user->name) }}" required
                            placeholder="Nama penerima paket"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('recipient_name') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        @error('recipient_name')
                            <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-xs font-bold text-slate-700 mb-1">
                            Nomor Telepon / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone_number" id="phone_number" 
                            value="{{ old('phone_number', $primaryAddress ? $primaryAddress->phone_number : '') }}" required
                            placeholder="Contoh: 081234567890"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('phone_number') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                        @error('phone_number')
                            <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address_line" class="block text-xs font-bold text-slate-700 mb-1">
                        Alamat Lengkap (Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan) <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="address_line" id="address_line" rows="2" required
                        placeholder="Contoh: Jl. Sudirman No. 123, RT 02/RW 04, Kel. Menteng, Kec. Menteng"
                        class="w-full px-3.5 py-2 bg-slate-50 border @error('address_line') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">{{ old('address_line', $primaryAddress ? $primaryAddress->address_line : '') }}</textarea>
                    @error('address_line')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-xs font-bold text-slate-700 mb-1">
                            Kota / Kabupaten <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="city" id="city" 
                            value="{{ old('city', $primaryAddress ? $primaryAddress->city : 'Jakarta Selatan') }}" required
                            class="w-full px-3.5 py-2 bg-slate-50 border @error('city') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    </div>

                    <div>
                        <label for="province" class="block text-xs font-bold text-slate-700 mb-1">
                            Provinsi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="province" id="province" 
                            value="{{ old('province', $primaryAddress ? $primaryAddress->province : 'DKI Jakarta') }}" required
                            class="w-full px-3.5 py-2 bg-slate-50 border @error('province') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    </div>

                    <div>
                        <label for="postal_code" class="block text-xs font-bold text-slate-700 mb-1">
                            Kode Pos <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="postal_code" id="postal_code" 
                            value="{{ old('postal_code', $primaryAddress ? $primaryAddress->postal_code : '12190') }}" required
                            class="w-full px-3.5 py-2 bg-slate-50 border @error('postal_code') border-rose-300 @else border-slate-300 @enderror rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    </div>
                </div>
            </div>

            <!-- 2. PILIHAN JASA KURIR -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center space-x-3 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-full bg-[#0B5CFF] text-white flex items-center justify-center font-black text-xs">2</span>
                    <h2 class="text-sm font-extrabold text-slate-900">Pilihan Jasa Ekspedisi Pengiriman</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    
                    <label class="border rounded-2xl p-4 cursor-pointer transition block relative"
                        :class="courier === 'jne' ? 'border-[#0B5CFF] bg-blue-50/50 ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="shipping_courier" value="jne" x-model="courier" class="sr-only">
                        <div class="font-bold text-xs text-slate-900">JNE Reguler Express</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Estimasi 1-2 Hari Kerja</div>
                        <div class="text-xs font-extrabold text-[#0B5CFF] mt-2">Rp 15.000 / kg</div>
                    </label>

                    <label class="border rounded-2xl p-4 cursor-pointer transition block relative"
                        :class="courier === 'sicepat' ? 'border-[#0B5CFF] bg-blue-50/50 ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="shipping_courier" value="sicepat" x-model="courier" class="sr-only">
                        <div class="font-bold text-xs text-slate-900">SiCepat BEST / GOKIL</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Estimasi 1-3 Hari Kerja</div>
                        <div class="text-xs font-extrabold text-[#0B5CFF] mt-2">Rp 14.000 / kg</div>
                    </label>

                    <label class="border rounded-2xl p-4 cursor-pointer transition block relative"
                        :class="courier === 'jnt' ? 'border-[#0B5CFF] bg-blue-50/50 ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="shipping_courier" value="jnt" x-model="courier" class="sr-only">
                        <div class="font-bold text-xs text-slate-900">J&T Express Super</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Estimasi 1-2 Hari Kerja</div>
                        <div class="text-xs font-extrabold text-[#0B5CFF] mt-2">Rp 16.000 / kg</div>
                    </label>

                </div>
            </div>

            <!-- 3. METODE PEMBAYARAN -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-4" x-data="{ payment: 'bca_va' }">
                <div class="flex items-center space-x-3 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-full bg-[#0B5CFF] text-white flex items-center justify-center font-black text-xs">3</span>
                    <h2 class="text-sm font-extrabold text-slate-900">Metode Pembayaran Resmi</h2>
                </div>

                <div class="space-y-2.5">
                    
                    <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition"
                        :class="payment === 'bca_va' ? 'border-[#0B5CFF] bg-blue-50/40 ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="bca_va" x-model="payment" class="text-[#0B5CFF]">
                            <div>
                                <p class="text-xs font-bold text-slate-900">BCA Virtual Account</p>
                                <p class="text-[11px] text-slate-500">Verifikasi instan otomatis 24 jam</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold rounded-md">BCA</span>
                    </label>

                    <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition"
                        :class="payment === 'mandiri_va' ? 'border-[#0B5CFF] bg-blue-50/40 ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="mandiri_va" x-model="payment" class="text-[#0B5CFF]">
                            <div>
                                <p class="text-xs font-bold text-slate-900">Mandiri Virtual Account</p>
                                <p class="text-[11px] text-slate-500">Verifikasi instan via Livin' by Mandiri</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">MANDIRI</span>
                    </label>

                    <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition"
                        :class="payment === 'bri_va' ? 'border-[#0B5CFF] bg-blue-50/40 ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="bri_va" x-model="payment" class="text-[#0B5CFF]">
                            <div>
                                <p class="text-xs font-bold text-slate-900">BRI Virtual Account (BRIVA)</p>
                                <p class="text-[11px] text-slate-500">Verifikasi instan via BRImo</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold rounded-md">BRI</span>
                    </label>

                    <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition"
                        :class="payment === 'qris' ? 'border-[#0B5CFF] bg-blue-50/40 ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="qris" x-model="payment" class="text-[#0B5CFF]">
                            <div>
                                <p class="text-xs font-bold text-slate-900">QRIS Instant Payment</p>
                                <p class="text-[11px] text-slate-500">Pindai kode QR via GoPay, OVO, Dana, ShopeePay & Mobile Banking</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold rounded-md">QRIS</span>
                    </label>

                    <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition"
                        :class="payment === 'bank_transfer' ? 'border-[#0B5CFF] bg-blue-50/40 ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="bank_transfer" x-model="payment" class="text-[#0B5CFF]">
                            <div>
                                <p class="text-xs font-bold text-slate-900">Transfer Bank Manual (Rekening PT LEOGATISTORE)</p>
                                <p class="text-[11px] text-slate-500">Transfer langsung ke rekening resmi perusahaan</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-md">TRANSFER</span>
                    </label>

                </div>
            </div>

            <!-- 4. CATATAN PESANAN -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Catatan Tambahan (Opsional)</h3>
                <textarea name="notes" rows="2" placeholder="Tuliskan instruksi pengemasan khusus atau patokan alamat..."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">{{ old('notes') }}</textarea>
            </div>

        </div>

        <!-- RIGHT: ORDER SUMMARY & SUBMIT -->
        <div class="lg:col-span-4 space-y-4">
            
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3">
                    Rincian Pesanan ({{ $cart->total_quantity }} Barang)
                </h3>

                <!-- ITEMS LIST -->
                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                    @foreach($cart->items as $item)
                        @php
                            $variant = $item->variant;
                            $product = $variant ? $variant->product : null;
                        @endphp
                        @if($product)
                            <div class="flex items-center justify-between text-xs py-1 border-b border-slate-50">
                                <div class="max-w-[190px]">
                                    <p class="font-bold text-slate-900 truncate">{{ $product->name }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $item->quantity }}x @ {{ rupiah($variant->price) }}</p>
                                </div>
                                <span class="font-extrabold text-slate-900">{{ rupiah($item->subtotal) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- PRICE CALCULATIONS -->
                <div class="space-y-2.5 text-xs pt-2 border-t border-slate-100">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal Produk</span>
                        <span class="font-bold text-slate-900">{{ rupiah($cart->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Total Berat</span>
                        <span class="font-bold text-slate-900">{{ number_format($cart->total_weight / 1000, 1) }} kg</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Pengiriman</span>
                        <span class="font-bold text-slate-900" x-text="formatRupiah(shippingCost)">
                            Rp 15.000
                        </span>
                    </div>

                    @if($appliedCoupon && $discountAmount > 0)
                        <div class="flex justify-between text-emerald-600 font-bold">
                            <span>Diskon Kupon ({{ $appliedCoupon->code }})</span>
                            <span>- {{ rupiah($discountAmount) }}</span>
                        </div>
                        <input type="hidden" name="coupon_code" value="{{ $appliedCoupon->code }}">
                    @endif

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Total Pembayaran</span>
                        <span class="text-xl font-black text-[#0B5CFF]" x-text="formatRupiah(grandTotal)">
                            {{ rupiah(max(0, $cart->subtotal + 15000 - $discountAmount)) }}
                        </span>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" 
                    class="w-full py-3.5 px-4 bg-[#0B5CFF] hover:bg-[#063B9E] text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Konfirmasi & Buat Pesanan</span>
                </button>
            </div>

            <!-- SECURITY BADGE -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs text-slate-600 space-y-1">
                <p class="font-bold text-slate-900 flex items-center">
                    <svg class="w-4 h-4 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Transaksi Terenkripsi & Aman
                </p>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Setiap transaksi dilindungi sistem nomor pesanan unik dan garansi distributor resmi berstempel garansi.
                </p>
            </div>

        </div>

    </form>

</div>
@endsection
