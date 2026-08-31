@extends('layouts.app')

@section('title', 'Pusat Komputer, Laptop & Komponen PC Bergaransi Resmi')

@section('content')
<div class="space-y-12 pb-16">

    <!-- HERO SECTION (RESPONSIVE BACKGROUND SLIDER) -->
    <section class="relative text-white py-12 sm:py-16 lg:py-20 overflow-hidden min-h-[480px] sm:min-h-[540px] lg:min-h-[580px] flex items-center bg-[#071A3D] select-none"
        x-data="{ 
            activeSlide: 0, 
            totalSlides: {{ isset($heroBanners) ? count($heroBanners) : 0 }}, 
            timer: null,
            intervalMs: 4500,
            progress: 0,
            progressInterval: null,
            touchStartX: 0,
            touchEndX: 0,
            
            init() {
                if (this.totalSlides > 1) {
                    this.startAutoPlay();
                }
            },
            startAutoPlay() {
                this.stopAutoPlay();
                this.progress = 0;
                
                // Progress timer bar (updates every 50ms)
                const step = (50 / this.intervalMs) * 100;
                this.progressInterval = setInterval(() => {
                    if (this.progress < 100) {
                        this.progress += step;
                    }
                }, 50);

                this.timer = setInterval(() => {
                    this.nextSlide();
                }, this.intervalMs);
            },
            stopAutoPlay() {
                if (this.timer) clearInterval(this.timer);
                if (this.progressInterval) clearInterval(this.progressInterval);
                this.progress = 0;
            },
            nextSlide() {
                this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                this.resetProgress();
            },
            prevSlide() {
                this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                this.resetProgress();
            },
            goToSlide(index) {
                this.activeSlide = index;
                this.resetProgress();
            },
            resetProgress() {
                if (this.totalSlides > 1) {
                    this.startAutoPlay();
                }
            },
            handleTouchStart(e) {
                this.touchStartX = e.touches[0].clientX;
                this.stopAutoPlay();
            },
            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].clientX;
                const diff = this.touchStartX - this.touchEndX;
                if (Math.abs(diff) > 40) {
                    if (diff > 0) {
                        this.nextSlide();
                    } else {
                        this.prevSlide();
                    }
                } else if (this.totalSlides > 1) {
                    this.startAutoPlay();
                }
            }
        }"
        @mouseenter="stopAutoPlay()"
        @mouseleave="if (totalSlides > 1) startAutoPlay()"
        @touchstart="handleTouchStart($event)"
        @touchend="handleTouchEnd($event)">

        <!-- BACKGROUND IMAGE SLIDES WITH OVERLAY -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            @if(isset($heroBanners) && count($heroBanners) > 0)
                @foreach($heroBanners as $index => $slide)
                    <div x-show="activeSlide === {{ $index }}"
                         x-transition:enter="transition-all ease-out duration-1000"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition-all ease-in duration-700"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-1000"
                         style="background-image: url('{{ $slide->image_url }}');"
                         x-cloak>
                    </div>
                @endforeach
            @else
                <!-- DEFAULT GRADIENT BACKGROUND IF NO SLIDES -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#071A3D] via-[#0B2559] to-[#0B5CFF]"></div>
            @endif

            <!-- DARK GLASS & GRADIENT OVERLAY FOR MAXIMUM LEGIBILITY ON MOBILE & DESKTOP -->
            <div class="absolute inset-0 bg-gradient-to-b sm:bg-gradient-to-r from-[#071A3D]/95 via-[#071A3D]/85 to-[#071A3D]/60 sm:to-[#071A3D]/40 backdrop-blur-[2px]"></div>
            <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        </div>

        <!-- MAIN HERO CONTENT CONTAINER -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- LEFT CONTENT COLUMN (RESPONSIVE TYPOGRAPHY & BUTTONS) -->
                <div class="lg:col-span-7 space-y-4 sm:space-y-6 text-center lg:text-left">
                    @if(isset($heroBanners) && count($heroBanners) > 0)
                        @foreach($heroBanners as $index => $slide)
                            <div x-show="activeSlide === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-500 delay-150"
                                 x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-cloak
                                 class="space-y-4 sm:space-y-6">

                                <div class="inline-flex items-center space-x-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-blue-200 text-[11px] sm:text-xs font-semibold shadow-inner">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                    <span class="truncate max-w-[280px] sm:max-w-none">{{ $slide->badge_text ?? 'Platform E-Commerce Teknologi Resmi Indonesia' }}</span>
                                </div>

                                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white drop-shadow-md">
                                    {{ $slide->title ?? 'Solusi Lengkap Komputer, Laptop & PC Rakitan Masa Depan' }}
                                </h1>

                                <p class="text-xs sm:text-sm lg:text-base text-slate-200 max-w-2xl leading-relaxed drop-shadow-xs mx-auto lg:mx-0">
                                    {{ $slide->subtitle ?? 'Temukan berbagai perangkat laptop terbaik, komponen PC original, simulasi rakit PC dengan mesin kompatibilitas cerdas, dan pengecekan garansi resmi yang transparan.' }}
                                </p>

                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-1 sm:pt-2">
                                    @if($slide->button_text && $slide->button_url)
                                        <a href="{{ $slide->button_url }}" class="w-full sm:w-auto px-6 py-3 sm:py-3.5 bg-[#0B5CFF] hover:bg-blue-600 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-lg shadow-blue-500/30 transition flex items-center justify-center transform hover:-translate-y-0.5">
                                            {{ $slide->button_text }}
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    @else
                                        <a href="{{ route('pc_builder.index') }}" class="w-full sm:w-auto px-6 py-3 sm:py-3.5 bg-white text-[#071A3D] hover:bg-slate-100 font-extrabold text-xs sm:text-sm rounded-xl shadow-lg transition flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2 text-[#0B5CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                            Mulai Simulasi PC Builder
                                        </a>
                                    @endif

                                    <a href="{{ route('warranty.check') }}" class="w-full sm:w-auto px-6 py-3 sm:py-3.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs sm:text-sm rounded-xl backdrop-blur-md transition flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        Cek Garansi Nomor Seri
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- FALLBACK IF NO SLIDES -->
                        <div class="space-y-4 sm:space-y-6">
                            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-blue-200 text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>Platform E-Commerce Teknologi Resmi Indonesia</span>
                            </div>

                            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                                Solusi Lengkap Komputer, Laptop & PC Rakitan Masa Depan
                            </h1>

                            <p class="text-xs sm:text-sm lg:text-base text-slate-200 max-w-2xl leading-relaxed mx-auto lg:mx-0">
                                Temukan berbagai perangkat laptop terbaik, komponen PC original, simulasi rakit PC dengan mesin kompatibilitas cerdas, dan pengecekan garansi resmi yang transparan.
                            </p>

                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-2">
                                <a href="{{ route('pc_builder.index') }}" class="w-full sm:w-auto px-6 py-3.5 bg-white text-[#071A3D] hover:bg-slate-100 font-extrabold text-xs sm:text-sm rounded-xl shadow-lg transition flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2 text-[#0B5CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                    Mulai Simulasi PC Builder
                                </a>
                                <a href="{{ route('warranty.check') }}" class="w-full sm:w-auto px-6 py-3.5 bg-blue-600/40 hover:bg-blue-600/60 border border-white/20 text-white font-bold text-xs sm:text-sm rounded-xl backdrop-blur-md transition flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Cek Garansi Nomor Seri
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- RIGHT: HERO CARD / STATS (RESPONSIVE) -->
                <div class="lg:col-span-5">
                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-5 sm:p-8 shadow-2xl text-white space-y-4 sm:space-y-6">
                        <div class="flex items-center justify-between border-b border-white/10 pb-3 sm:pb-4">
                            <div>
                                <p class="text-[10px] sm:text-xs text-blue-200 font-semibold uppercase tracking-wider">Garansi Transparan</p>
                                <h3 class="text-base sm:text-lg font-bold">Pusat Pengecekan Cepat</h3>
                            </div>
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                        </div>

                        <form action="{{ route('warranty.check') }}" method="GET" class="space-y-2.5 sm:space-y-3">
                            <label class="block text-xs font-semibold text-blue-100">Cek Status Garansi Produk Anda:</label>
                            <div class="flex gap-2">
                                <input type="text" name="sn" placeholder="Nomor Seri (S/N)..." 
                                    class="flex-1 px-3.5 py-2 sm:px-4 sm:py-2.5 bg-white/20 border border-white/30 rounded-xl text-xs text-white placeholder-slate-300 focus:outline-hidden focus:bg-white/30 focus:border-white">
                                <button type="submit" class="px-4 py-2 sm:py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-md shrink-0">
                                    Periksa
                                </button>
                            </div>
                        </form>

                        <div class="grid grid-cols-2 gap-3 sm:gap-4 pt-1 sm:pt-2 border-t border-white/10 text-center">
                            <div class="bg-white/5 rounded-xl p-2.5 sm:p-3">
                                <p class="text-lg sm:text-xl font-extrabold text-white">100%</p>
                                <p class="text-[10px] sm:text-[11px] text-blue-200">Resmi Distributor</p>
                            </div>
                            <div class="bg-white/5 rounded-xl p-2.5 sm:p-3">
                                <p class="text-lg sm:text-xl font-extrabold text-white">24 Jam</p>
                                <p class="text-[10px] sm:text-[11px] text-blue-200">Dukungan Teknis</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SLIDER CONTROLS & PAGINATION (FULLY RESPONSIVE) -->
            @if(isset($heroBanners) && count($heroBanners) > 1)
                <div class="flex items-center justify-between pt-6 sm:pt-8 border-t border-white/10 mt-6 sm:mt-8">
                    <!-- PREV / NEXT ARROW BUTTONS -->
                    <div class="flex items-center space-x-2">
                        <button @click="prevSlide()" class="p-2 sm:p-2.5 rounded-xl bg-white/10 hover:bg-white/20 active:scale-95 border border-white/20 text-white transition focus:outline-hidden" title="Slide Sebelumnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="nextSlide()" class="p-2 sm:p-2.5 rounded-xl bg-white/10 hover:bg-white/20 active:scale-95 border border-white/20 text-white transition focus:outline-hidden" title="Slide Selanjutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <!-- INDICATOR PILLS WITH PROGRESS TIMER -->
                    <div class="flex items-center space-x-2">
                        @foreach($heroBanners as $index => $slide)
                            <button @click="goToSlide({{ $index }})" 
                                    class="h-2 sm:h-2.5 rounded-full transition-all duration-300 focus:outline-hidden relative overflow-hidden"
                                    :class="activeSlide === {{ $index }} ? 'w-8 sm:w-12 bg-white/30' : 'w-2 sm:w-3 bg-white/20 hover:bg-white/50'"
                                    title="Ke Slide {{ $index + 1 }}">
                                <span x-show="activeSlide === {{ $index }}" 
                                      class="absolute inset-0 bg-[#0B5CFF] rounded-full transition-all"
                                      :style="'width: ' + progress + '%'"></span>
                            </button>
                        @endforeach
                    </div>

                    <span class="text-[11px] sm:text-xs text-blue-200 font-bold tracking-wider">
                        <span x-text="activeSlide + 1"></span> / {{ count($heroBanners) }}
                    </span>
                </div>
            @endif
        </div>
    </section>

    <!-- VALUE PROPOSITIONS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-start space-x-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0B5CFF] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">100% Garansi Resmi</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Seluruh barang dijamin original dari distributor resmi di Indonesia.</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-start space-x-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Perakitan PC Ahli</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Dirakit oleh teknisi berpengalaman dengan manajemen kabel rapi dan uji stres.</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-start space-x-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Pengiriman Aman</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Packing kayu dan asuransi penuh untuk pengiriman ke seluruh pelosok Nusantara.</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-start space-x-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Bantuan Responsif</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Konsultasi pemilihan spesifikasi perangkat dan klaim garansi tanpa ribet.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- CATEGORIES SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">Kategori Pilihan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Jelajahi beragam kategori produk teknologi terkini</p>
            </div>
            <a href="#" class="text-xs font-bold text-[#0B5CFF] hover:underline flex items-center">
                Lihat Semua Kategori
                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-[#0B5CFF] hover:shadow-md transition group text-left">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 group-hover:bg-[#0B5CFF] text-[#0B5CFF] group-hover:text-white flex items-center justify-center transition mb-3">
                        {!! $category->renderIcon('w-5 h-5') !!}
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-[#0B5CFF] transition truncate">{{ $category->name }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $category->description }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <!-- FEATURED PRODUCTS SECTION -->
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">Produk Unggulan Pilihan</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Laptop, prosesor, dan kartu grafis resmi paling diminati</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-[#0B5CFF] hover:underline flex items-center">
                    Lihat Semua Produk
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($featuredProducts as $product)
                    @php
                        $defaultVariant = $product->defaultVariant;
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200/80 hover:border-[#0B5CFF] hover:shadow-md transition flex flex-col justify-between overflow-hidden group">
                        <div class="p-4 space-y-3">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="px-2 py-0.5 bg-blue-50 text-[#0B5CFF] font-bold rounded-md">
                                    {{ $product->brand->name }}
                                </span>
                                <span class="text-slate-500 font-medium flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    {{ $product->warranty_period_months }} Bln
                                </span>
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-36 bg-slate-50 rounded-xl overflow-hidden flex items-center justify-center p-3">
                                {!! $product->renderThumbnail('max-h-full max-w-full object-contain group-hover:scale-105 transition duration-300', 'w-7 h-7') !!}
                            </a>

                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-[#0B5CFF] transition line-clamp-2">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $product->short_description }}</p>
                            </div>
                        </div>

                        <div class="p-4 pt-0 border-t border-slate-50 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block">Harga</span>
                                <p class="text-sm font-extrabold text-[#0B5CFF]">
                                    {{ rupiah($defaultVariant ? $defaultVariant->price : 0) }}
                                </p>
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-[#0B5CFF] text-slate-700 hover:text-white font-bold text-xs rounded-xl transition">
                                Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- PC BUILDER BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-linear-to-r from-[#071A3D] via-[#0B2559] to-[#0B5CFF] rounded-3xl p-6 sm:p-10 lg:p-12 text-white relative overflow-hidden shadow-xl border border-blue-900/30">
            <!-- Background Glow Decor -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-400/15 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-8 items-center relative z-10">
                <div class="md:col-span-7 lg:col-span-8 space-y-4 text-center md:text-left">
                    <span class="inline-block px-3 py-1 bg-amber-400 text-slate-950 text-xs font-black uppercase rounded-lg tracking-wider shadow-xs">
                        Fitur Unggulan
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight">
                        Rakit Komputer Impian dengan Cek Kompatibilitas Cerdas
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-200 leading-relaxed max-w-2xl">
                        Sistem LEOGATISTORE memeriksa kecocokan soket prosesor, motherboard, konsumsi daya power supply (PSU), hingga dimensi casing secara otomatis.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-3">
                        <a href="{{ route('pc_builder.index') }}" class="inline-flex items-center px-6 py-3.5 bg-[#0B5CFF] hover:bg-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            Buka Simulator PC Builder
                        </a>
                    </div>
                </div>

                <!-- Mascot Illustration -->
                <div class="md:col-span-5 lg:col-span-4 flex justify-center md:justify-end">
                    <div class="relative w-44 sm:w-56 md:w-64 lg:w-72 max-w-full">
                        <img src="{{ asset('images/logo/tembak ungu rambut grey.png') }}" 
                             alt="LEOGATISTORE Gaming Mascot" 
                             class="w-full h-auto object-contain drop-shadow-[0_15px_25px_rgba(0,0,0,0.45)] transition-transform duration-300 hover:scale-105">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BRANDS SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-8">
            <h2 class="text-xl font-extrabold text-slate-900">Merek Resmi Mitra Terpercaya</h2>
            <p class="text-xs text-slate-500 mt-1">Kami bermitra langsung dengan produsen hardware dan teknologi terkemuka dunia</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-3.5">
            @foreach($brands as $brand)
                <a href="{{ route('brands.show', $brand->slug) }}" class="bg-white px-3 py-3.5 rounded-2xl border border-slate-200/80 flex items-center justify-center text-center hover:border-[#0B5CFF] hover:shadow-md transition group h-20">
                    <div class="h-7 w-full flex items-center justify-center transition group-hover:scale-105">
                        {!! $brand->renderLogo('h-6 w-auto max-h-7 max-w-[100px] object-contain') !!}
                    </div>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
