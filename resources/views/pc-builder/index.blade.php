@extends('layouts.app')

@section('title', 'Simulator PC Builder & Pengecekan Kompatibilitas Hardware')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="pcBuilderApp()">

    <!-- HEADER / HERO -->
    <div class="bg-linear-to-r from-[#071A3D] via-[#063B9E] to-[#0B5CFF] rounded-3xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-3xl space-y-3">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-200 text-xs font-bold border border-blue-400/30">
                <svg class="w-4 h-4 mr-1 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                LEOGATISTORE PC Builder Engine v2.0
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                Simulasi Rakit PC & Pengecekan Kompatibilitas Otomatis
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                Pilih komponen PC impian Anda. Sistem kami secara otomatis memeriksa kecocokan soket CPU, form factor motherboard, tipe RAM, dimensi casing, serta menghitung estimasi kebutuhan daya Power Supply secara real-time.
            </p>
        </div>
    </div>

    <!-- MAIN BUILDER GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- LEFT 2 COLUMNS: COMPONENT SLOTS SELECTION -->
        <div class="lg:col-span-2 space-y-4">
            
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h2 class="text-lg font-extrabold text-slate-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#0B5CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    Daftar Komponen Rakitan
                </h2>
                <button type="button" @click="resetBuild()" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition">
                    Kosongkan Semua
                </button>
            </div>

            <!-- COMPONENT SLOTS -->
            @php
                $slots = [
                    ['key' => 'cpu', 'name' => 'Prosesor (CPU)', 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'desc' => 'Otak utama pemrosesan komputer'],
                    ['key' => 'motherboard', 'name' => 'Motherboard', 'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z', 'desc' => 'Papan sirkuit utama penghubung seluruh komponen'],
                    ['key' => 'ram', 'name' => 'Memori (RAM)', 'icon' => 'M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z', 'desc' => 'Memori akses acak berkecepatan tinggi'],
                    ['key' => 'gpu', 'name' => 'Kartu Grafis (VGA / GPU)', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'desc' => 'Pengolah grafis gaming dan rendering visual 3D'],
                    ['key' => 'storage', 'name' => 'Media Penyimpanan (SSD)', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'desc' => 'Penyimpanan sistem operasi dan file berkecepatan tinggi'],
                    ['key' => 'psu', 'name' => 'Power Supply Unit (PSU)', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'desc' => 'Penyuplai daya listrik dengan efisiensi terjamin'],
                    ['key' => 'casing', 'name' => 'Casing PC', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'desc' => 'Rangka penampung dengan aliran sirkulasi udara'],
                    ['key' => 'cooler', 'name' => 'Pendingin CPU (Cooler)', 'icon' => 'M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5', 'desc' => 'Sistem pendingin cairan atau udara untuk menjaga kestabilan temperatur'],
                ];
            @endphp

            @foreach($slots as $slot)
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-2xs hover:border-blue-200 transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $slot['icon'] }}"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">{{ $slot['name'] }}</h3>
                            <p class="text-[11px] text-slate-400">{{ $slot['desc'] }}</p>
                        </div>
                    </div>

                    <div class="sm:w-80">
                        <select x-model="selectedComponents.{{ $slot['key'] }}" @change="checkCompatibility()"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-hidden focus:border-[#0B5CFF] focus:ring-2 focus:ring-blue-100 transition">
                            <option value="">-- Pilih {{ $slot['name'] }} --</option>
                            @if(isset($categorizedComponents[$slot['key']]))
                                @foreach($categorizedComponents[$slot['key']] as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->product->name }} — {{ rupiah($variant->price) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                </div>
            </div>
            @endforeach

        </div>

        <!-- RIGHT 1 COLUMN: LIVE STATUS & POWER CONSUMPTION GAUGE & ACTIONS -->
        <div class="space-y-6">
            
            <!-- STICKY SUMMARY CARD -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-md space-y-6 sticky top-6">
                
                <h3 class="text-base font-extrabold text-slate-900 pb-3 border-b border-slate-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#0B5CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Ringkasan & Kompatibilitas
                </h3>

                <!-- COMPATIBILITY BADGE -->
                <div class="p-4 rounded-2xl border transition"
                     :class="{
                        'bg-emerald-50 border-emerald-200 text-emerald-800': compStatus === 'compatible',
                        'bg-amber-50 border-amber-200 text-amber-800': compStatus === 'warning',
                        'bg-rose-50 border-rose-200 text-rose-800': compStatus === 'incompatible'
                     }">
                    <div class="flex items-center space-x-2 font-bold text-xs">
                        <template x-if="compStatus === 'compatible'">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                        <template x-if="compStatus === 'warning'">
                            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </template>
                        <template x-if="compStatus === 'incompatible'">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                        <span x-text="compStatusLabel">Kompatibel & Siap Dirakit</span>
                    </div>
                </div>

                <!-- COMPATIBILITY MESSAGES ACCORDION -->
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1 text-xs" x-show="compMessages.length > 0">
                    <template x-for="(msg, i) in compMessages" :key="i">
                        <div class="p-2.5 rounded-xl border text-[11px] leading-relaxed"
                             :class="{
                                'bg-emerald-50/50 border-emerald-100 text-emerald-900': msg.type === 'compatible',
                                'bg-amber-50/50 border-amber-100 text-amber-900': msg.type === 'warning',
                                'bg-rose-50/50 border-rose-100 text-rose-900 font-semibold': msg.type === 'incompatible'
                             }">
                            <strong x-text="msg.title" class="block mb-0.5"></strong>
                            <span x-text="msg.message"></span>
                        </div>
                    </template>
                </div>

                <!-- POWER METER / WATTAGE -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Estimasi Konsumsi Daya</span>
                        <span class="font-extrabold text-slate-800 font-mono text-sm"><span x-text="estimatedWattage">0</span> Watt</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-[#0B5CFF] h-2 rounded-full transition-all duration-500" :style="'width: ' + Math.min(100, (estimatedWattage / 1000) * 100) + '%'"></div>
                    </div>
                    <div class="flex justify-between items-center text-[11px] text-slate-500 pt-1">
                        <span>Rekomendasi PSU Minimal:</span>
                        <span class="font-bold text-blue-600 font-mono"><span x-text="recommendedPsu">450</span> Watt</span>
                    </div>
                </div>

                <!-- TOTAL PRICE -->
                <div class="pt-2 border-t border-slate-100">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Estimasi Harga</div>
                    <div class="text-2xl font-black text-[#0B5CFF] tracking-tight mt-1" x-text="totalPriceIdr">
                        Rp 0
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="space-y-2 pt-2">
                    <!-- ADD ALL TO CART FORM -->
                    <form method="POST" action="{{ route('pc_builder.add_to_cart') }}">
                        @csrf
                        <template x-for="(val, key) in selectedComponents" :key="key">
                            <input type="hidden" :name="'components[' + key + ']'" :value="val">
                        </template>
                        <button type="submit" 
                                class="w-full py-3 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="compStatus === 'incompatible' || Object.values(selectedComponents).filter(v => v !== '').length === 0">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Masukkan Rakitan ke Keranjang
                        </button>
                    </form>

                    <!-- SAVE & SHARE BUILD BUTTON -->
                    <button type="button" @click="saveBuild()"
                            class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Simpan & Bagikan Rakitan
                    </button>
                </div>

            </div>

        </div>

    </div>

    <!-- MODAL SHARE LINK -->
    <div x-show="shareModalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4" @click.away="shareModalOpen = false">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center font-bold text-xl mx-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="text-center space-y-1">
                <h3 class="text-base font-bold text-slate-900">Rakitan PC Berhasil Disimpan!</h3>
                <p class="text-xs text-slate-500">Gunakan tautan unik ini untuk membagikan atau membuka kembali rakitan Anda kapan saja.</p>
            </div>
            <div class="flex items-center space-x-2 bg-slate-50 border border-slate-200 rounded-xl p-2">
                <input type="text" readonly :value="shareUrl" class="w-full bg-transparent border-0 text-xs font-mono text-slate-800 focus:outline-hidden">
                <button type="button" @click="navigator.clipboard.writeText(shareUrl); copied = true; setTimeout(() => copied = false, 2000)"
                        class="px-3 py-1.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-lg shrink-0 transition">
                    <span x-text="copied ? 'Tersalin!' : 'Salin'">Salin</span>
                </button>
            </div>
            <button type="button" @click="shareModalOpen = false" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                Tutup
            </button>
        </div>
    </div>

</div>

<script>
function pcBuilderApp() {
    return {
        selectedComponents: {
            cpu: '{{ $savedBuild ? ($savedBuild->components['cpu'] ?? '') : '' }}',
            motherboard: '{{ $savedBuild ? ($savedBuild->components['motherboard'] ?? '') : '' }}',
            ram: '{{ $savedBuild ? ($savedBuild->components['ram'] ?? '') : '' }}',
            gpu: '{{ $savedBuild ? ($savedBuild->components['gpu'] ?? '') : '' }}',
            storage: '{{ $savedBuild ? ($savedBuild->components['storage'] ?? '') : '' }}',
            psu: '{{ $savedBuild ? ($savedBuild->components['psu'] ?? '') : '' }}',
            casing: '{{ $savedBuild ? ($savedBuild->components['casing'] ?? '') : '' }}',
            cooler: '{{ $savedBuild ? ($savedBuild->components['cooler'] ?? '') : '' }}',
        },
        compStatus: 'compatible',
        compStatusLabel: 'Kompatibel & Siap Dirakit',
        compMessages: [],
        estimatedWattage: 0,
        recommendedPsu: 450,
        totalPriceIdr: 'Rp 0',
        shareModalOpen: false,
        shareUrl: '',
        copied: false,

        init() {
            this.checkCompatibility();
        },

        async checkCompatibility() {
            try {
                const res = await fetch('{{ route('pc_builder.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ components: this.selectedComponents })
                });

                if (res.ok) {
                    const data = await res.json();
                    this.compStatus = data.status;
                    this.compStatusLabel = data.status_label;
                    this.compMessages = data.messages || [];
                    this.estimatedWattage = data.estimated_wattage || 0;
                    this.recommendedPsu = data.recommended_psu || 450;
                    this.totalPriceIdr = data.total_price_idr || 'Rp 0';
                }
            } catch (err) {
                console.error(err);
            }
        },

        resetBuild() {
            this.selectedComponents = {
                cpu: '', motherboard: '', ram: '', gpu: '', storage: '', psu: '', casing: '', cooler: ''
            };
            this.checkCompatibility();
        },

        async saveBuild() {
            const hasItems = Object.values(this.selectedComponents).some(v => v !== '');
            if (!hasItems) {
                alert('Pilih minimal satu komponen PC terlebih dahulu sebelum menyimpan rakitan.');
                return;
            }

            try {
                const res = await fetch('{{ route('pc_builder.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        build_name: 'Simulasi PC ' + new Date().toLocaleDateString('id-ID'),
                        components: this.selectedComponents
                    })
                });

                if (res.ok) {
                    const data = await res.json();
                    this.shareUrl = data.share_url;
                    this.shareModalOpen = true;
                }
            } catch (err) {
                console.error(err);
            }
        }
    };
}
</script>
@endsection
