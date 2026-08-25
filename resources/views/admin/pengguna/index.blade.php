@extends('layouts.admin')

@section('header_title', 'Manajemen Akun Pengguna & Staf')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Pengguna & Staf Operasional</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola akun administrator, staf gudang, sales, finance, dan pelanggan beserta hak akses (RBAC).</p>
        </div>
        <a href="{{ route('admin.pengguna.create') }}" 
            class="px-4 py-2.5 bg-[#0B5CFF] hover:bg-[#063B9E] text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Tambah Pengguna / Staf
        </a>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">
        <form action="{{ route('admin.pengguna.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email pengguna..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-hidden focus:border-[#0B5CFF] focus:bg-white transition">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="role" onchange="this.form.submit()"
                class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-[#0B5CFF]">
                <option value="">Semua Peran (Roles)</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>
                        {{ $r->display_name }} ({{ $r->name }})
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition">
                Filter
            </button>
            @if(request()->hasAny(['q', 'role']))
                <a href="{{ route('admin.pengguna.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200 transition flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- USERS TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-5 py-3.5">Nama & Profil</th>
                        <th class="px-5 py-3.5">Email Akun</th>
                        <th class="px-5 py-3.5">Peran / Hak Akses</th>
                        <th class="px-5 py-3.5">Terdaftar Sejak</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-linear-to-br from-[#0B5CFF] to-[#071A3D] text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <span>{{ $user->name }}</span>
                                        @if($user->id === auth()->id())
                                            <span class="ml-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-blue-100 text-blue-800">
                                                Anda
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($user->roles as $role)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold 
                                            @if($role->name === 'super_admin') bg-purple-50 text-purple-700 border border-purple-200
                                            @elseif($role->name === 'admin') bg-blue-50 text-blue-700 border border-blue-200
                                            @elseif($role->name === 'warehouse_staff') bg-amber-50 text-amber-700 border border-amber-200
                                            @elseif($role->name === 'finance_staff') bg-emerald-50 text-emerald-700 border border-emerald-200
                                            @elseif($role->name === 'sales_staff') bg-indigo-50 text-indigo-700 border border-indigo-200
                                            @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                            {{ $role->display_name }}
                                        </span>
                                    @empty
                                        <span class="text-slate-400">Tidak ada peran</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-500">
                                {{ $user->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.pengguna.edit', $user->id) }}" 
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-[#0B5CFF] text-slate-700 hover:text-white font-bold rounded-lg transition">
                                        Ubah
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Hapus akun pengguna {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white font-bold rounded-lg transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-500">
                                Tidak ada akun pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
