@extends('layouts.admin')

@section('header_title', 'Manajemen Banner Hero Background')

@section('content')
<div class="space-y-6">

    <!-- HEADER & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Background Slider Hero Storefront</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola gambar latar belakang slider hero beranda, urutan tampilan, dan tautan promosi.</p>
        </div>
        <div>
            <a href="{{ route('admin.banner-hero.create') }}" class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-md flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Slide Hero Baru
            </a>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.banner-hero.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, subjudul, atau badge..."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
            </div>

            <div>
                <select name="status" class="px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF]">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                Filter
            </button>

            @if(request()->anyFilled(['q', 'status']))
                <a href="{{ route('admin.banner-hero.index') }}" class="px-3 py-2 text-xs text-rose-600 hover:underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- DATA TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5 w-12 text-center">Urutan</th>
                        <th class="px-5 py-3.5">Pratinjau Background</th>
                        <th class="px-5 py-3.5">Judul & Teks</th>
                        <th class="px-5 py-3.5">Tautan CTA</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 text-center font-extrabold text-slate-900">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center mx-auto text-xs">
                                    {{ $banner->sort_order }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="w-36 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-900 relative shadow-2xs group">
                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Hero Background' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 to-transparent"></div>
                                </div>
                            </td>
                            <td class="px-5 py-4 space-y-1 max-w-xs">
                                @if($banner->badge_text)
                                    <span class="inline-block px-2 py-0.5 bg-blue-100 text-[#0B5CFF] text-[10px] font-bold rounded-md">
                                        {{ $banner->badge_text }}
                                    </span>
                                @endif
                                <h4 class="font-bold text-slate-900 text-xs truncate">
                                    {{ $banner->title ?? '(Tanpa Judul Form)' }}
                                </h4>
                                <p class="text-[11px] text-slate-500 line-clamp-2">
                                    {{ $banner->subtitle ?? 'Menggunakan deskripsi standar toko' }}
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                @if($banner->button_text || $banner->button_url)
                                    <div class="space-y-0.5 text-[11px]">
                                        <p class="font-bold text-slate-800">{{ $banner->button_text ?? 'Tombol CTA' }}</p>
                                        <p class="text-blue-600 truncate max-w-[150px]">{{ $banner->button_url ?? '#' }}</p>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[11px]">Standar Storefront</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <form action="{{ route('admin.banner-hero.toggle', $banner->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold transition {{ $banner->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $banner->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                        {{ $banner->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-right space-x-1.5">
                                <a href="{{ route('admin.banner-hero.edit', $banner->id) }}" class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#0B5CFF] rounded-lg transition font-bold text-[11px]">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>

                                <form action="{{ route('admin.banner-hero.destroy', $banner->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide background hero ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition font-bold text-[11px]">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="font-bold text-slate-700">Belum ada slide background hero kustom.</p>
                                <p class="text-xs text-slate-500 mt-1">Storefront akan menampilkan background gradient default sampai Anda menambahkan slide baru.</p>
                                <a href="{{ route('admin.banner-hero.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-[#0B5CFF] text-white font-bold rounded-xl text-xs">
                                    Tambah Slide Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $banners->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
