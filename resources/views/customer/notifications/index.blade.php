@extends('layouts.app')

@section('title', 'Pusat Notifikasi - LEOGATISTORE')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- BREADCRUMBS --}}
    <nav class="flex text-xs font-semibold text-slate-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-[#0B5CFF]">Beranda</a></li>
            <li><span class="text-slate-400">/</span></li>
            <li><a href="{{ route('customer.dashboard') }}" class="hover:text-[#0B5CFF]">Akun Saya</a></li>
            <li><span class="text-slate-400">/</span></li>
            <li class="text-slate-900 font-bold">Notifikasi</li>
        </ol>
    </nav>

    {{-- HEADER & TANDAI SUDAH DIBACA --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-2.5">
                <span>Pusat Notifikasi</span>
                @if($unreadCount > 0)
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-500 text-white">
                        {{ $unreadCount }} Baru
                    </span>
                @endif
            </h1>
            <p class="text-xs text-slate-500 mt-1">Pemberitahuan status pesanan, pembayaran, pengiriman, dan layanan purna jual garansi.</p>
        </div>
        @if($unreadCount > 0)
            <form action="{{ route('customer.notifications.read_all') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#0B5CFF] hover:bg-blue-100 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- TABS FILTER --}}
    <div class="flex items-center space-x-2 border-b border-slate-200 mb-6">
        <a href="{{ route('customer.notifications.index', ['filter' => 'all']) }}"
           class="pb-3 px-3 text-xs font-bold border-b-2 transition {{ $filter === 'all' ? 'border-[#0B5CFF] text-[#0B5CFF]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            Semua Notifikasi
        </a>
        <a href="{{ route('customer.notifications.index', ['filter' => 'unread']) }}"
           class="pb-3 px-3 text-xs font-bold border-b-2 transition flex items-center gap-1.5 {{ $filter === 'unread' ? 'border-[#0B5CFF] text-[#0B5CFF]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            Belum Dibaca
            @if($unreadCount > 0)
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            @endif
        </a>
    </div>

    {{-- DAFTAR NOTIFIKASI --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden divide-y divide-slate-100">
        @forelse($notifications as $notif)
            @php
                $data = $notif->data;
                $isUnread = is_null($notif->read_at);
                $type = $data['type'] ?? 'info';
            @endphp
            <div class="p-5 flex items-start gap-4 transition hover:bg-slate-50 {{ $isUnread ? 'bg-blue-50/40' : '' }}">
                {{-- ICON TIPE NOTIFIKASI --}}
                <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center
                    {{ $type === 'order_created' ? 'bg-emerald-100 text-emerald-600' : '' }}
                    {{ $type === 'order_status_updated' ? 'bg-blue-100 text-[#0B5CFF]' : '' }}
                    {{ $type === 'warranty_claim_updated' ? 'bg-purple-100 text-purple-600' : '' }}
                    {{ !in_array($type, ['order_created', 'order_status_updated', 'warranty_claim_updated']) ? 'bg-slate-100 text-slate-600' : '' }}
                ">
                    @if($type === 'order_created')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    @elseif($type === 'order_status_updated')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($type === 'warranty_claim_updated')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    @endif
                </div>

                {{-- KONTEN NOTIFIKASI --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h4 class="text-sm font-black text-slate-900 flex items-center gap-2">
                            {{ $data['title'] ?? 'Pemberitahuan Sistem' }}
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-[#0B5CFF]"></span>
                            @endif
                        </h4>
                        <span class="text-[11px] text-slate-400 shrink-0">{{ tgl_indo($notif->created_at) }}</span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $data['message'] ?? '-' }}</p>

                    <div class="mt-3 flex items-center gap-3">
                        @if(!empty($data['url']))
                            <form action="{{ route('customer.notifications.read', $notif->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center text-xs font-bold text-[#0B5CFF] hover:underline">
                                    Lihat Detail &rarr;
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Tidak Ada Notifikasi</h3>
                <p class="text-xs text-slate-400 mt-1">Anda belum memiliki pemberitahuan baru saat ini.</p>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
@endsection
